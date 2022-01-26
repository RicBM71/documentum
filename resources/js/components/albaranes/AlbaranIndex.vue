<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                color="white"
                                icon
                                @click="filtro = !filtro"
                            >
                                <v-icon color="primary">filter_list</v-icon>
                            </v-btn>
                        </template>
                        <span>Filtrar</span>
                    </v-tooltip>
                <menu-ope :albaran="albaran"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card v-show="filtro">
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs3 d-flex>
                                <v-select
                                    v-model="quefecha"
                                    :items="fechas"
                                    label="Fecha"
                                ></v-select>
                            </v-flex>
                            <v-flex sm2>
                                <v-menu
                                    v-model="menu_d"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                >
                                    <v-text-field
                                        slot="activator"
                                        :value="computedFechaD"
                                        label="Desde"
                                        append-icon="event"
                                        v-validate="'date_format:dd/MM/yyyy'"
                                        data-vv-name="fecha_d"
                                        :error-messages="errors.collect('fecha_d')"
                                        data-vv-as="Desde"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="fecha_d"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu_d = false"
                                        ></v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex sm2>
                                <v-menu
                                    v-model="menu_h"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                >

                                    <v-text-field
                                        slot="activator"

                                        :value="computedFechaH"
                                        label="Hasta"
                                        append-icon="event"
                                        v-validate="'date_format:dd/MM/yyyy'"
                                        data-vv-name="fecha_h"
                                        :error-messages="errors.collect('fecha_h')"
                                        data-vv-as="Hasta"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="fecha_h"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu_h = false"
                                        ></v-date-picker>
                                </v-menu>
                            </v-flex>

                            <v-spacer></v-spacer>
                            <v-flex sm2>
                                <v-btn @click="filtrar"  round  block  color="info">
                                    Filtrar
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-card>
        <v-card>
            <v-container>
                <v-layout row wrap>
                    <v-flex xs6></v-flex>
                    <v-flex xs6>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="search"
                            append-icon="search"
                            label="Buscar"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <br/>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-data-table
                        v-show="!show_loading"
                        :headers="headers"
                        :items="albaranes"
                        :search="search"
                        @update:pagination="updateEventPagina"
                        :pagination.sync="pagination"
                        rows-per-page-text="Registros por página"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.alb_ser }}</td>
                                <td>{{ formatDate(props.item.fecha_alb) }}</td>
                                <td>{{ props.item.cliente.razon }}</td>
                                <td class="text-xs-right">{{ totalImpLinea(props.item.albalins) | currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false })}}</td>
                                <td>{{ props.item.factura }}</td>
                                <td>{{ formatDate(props.item.fecha_fac) }}</td>
                                <td>{{ Notificado(props.item.notificado) }}</td>
                                <td class="justify-center layout px-0">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="editItem(props.item.id)"
                                    >
                                        edit
                                    </v-icon>


                                    <v-icon
                                    v-if="props.item.eje_fac==0"
                                    small
                                    @click="openDialog(props.item.id)"
                                    >
                                    delete
                                    </v-icon>
                                </td>
                            </template>
                            <template slot="pageText" slot-scope="props">
                                Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
    </div>
</template>
<script>
import moment from 'moment';
import Loading from '@/components/shared/Loading'
import MyDialog from '@/components/shared/MyDialog'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";

  export default {
    $_veeValidate: {
      		validator: 'new'
    },
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        albaran:{
            id: 0,
            cliente_id: 0,
            eje_fac: 0
        },
        titulo:"Albaranes",
        search:"",
        paginaActual:{},
        pagination:{
            model: "albaranes",
            descending: true,
            page: 1,
            rowsPerPage: 10,
            sortBy: "factura",
            search: ""
        },
        headers: [
          {
            text: 'Albarán',
            align: 'center',
            value: 'albaran'
          },
          {
            text: 'Fecha',
            align: 'left',
            value: 'fecha_alb'
          },
          {
            text: 'Cliente',
            align: 'left',
            value: 'cliente.razon'
          },
          {
            text: 'Importe',
            align: 'right',
            value: 'importe',
            sortable: false
          },
          {
            text: 'Factura',
            align: 'Left',
            value: 'factura'
          },
          {
            text: 'F. Factura',
            align: 'Left',
            value: 'fecha_fac'
          },
          {
            text: 'N',
            align: 'Left',
            value: 'notificado'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        albaranes:[],
        status: false,
        dialog: false,

        producto_id: 0,

        quefecha: "A",
        fechas: [
                {text:'Albarán','value':'A'},
                {text:'Factura','value':'F'},
            ],

        show_loading: true,
        filtro: false,

        menu_d: false,
        menu_h: false,
        fecha_d: new Date().toISOString().substr(0, 5)+"01-01",
        fecha_h: new Date().toISOString().substr(0, 10),

      }
    },
    mounted()
    {

        if (this.getPagination.model == this.pagination.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        axios.get('/ventas/albacabs')
            .then(res => {
                
                this.albaranes = res.data;
                this.show_loading = false;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed:{
        ...mapGetters([
            'hasDocumenta',
            'getPagination'
        ]),
        computedFechaD() {
            moment.locale('es');
            return this.fecha_d ? moment(this.fecha_d).format('L') : '';
        },
        computedFechaH() {
            moment.locale('es');
            return this.fecha_h ? moment(this.fecha_h).format('L') : '';
        },
    },
    methods:{
         ...mapActions([
            'setPagination',
            'unsetPagination'
        ]),
        updateEventPagina(obj){

            this.paginaActual = obj;
            this.paginaActual.search = this.search;

        },
        Notificado(noti){
            return noti ? 'Ok' : '';
        },
        updatePosPagina(pag){

            this.search = pag.search;
            this.pagination.page = pag.page;
            this.pagination.descending = pag.descending;
            this.pagination.rowsPerPage= pag.rowsPerPage;
            this.pagination.sortBy = pag.sortBy;

        },

        formatDate(f){
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        totalImpLinea(lineas){
            var total = 0;
            lineas.map((lin) =>
            {
                var imp = parseFloat(lin.importe);
                var iva = parseFloat(lin.poriva)
                var irpf= parseFloat(lin.porirpf)
                total += (imp + (imp * iva / 100) - (imp * irpf / 100));

            })
            return total.toFixed(2);
        },
        filtrar(){
            this.$validator.validateAll().then((result) => {
                if (result){
                    this.show_loading = true;
                    axios.post('ventas/albacabs/filtrar',
                            {
                                fecha_d: this.fecha_d,
                                fecha_h: this.fecha_h,
                                fecha: this.quefecha
                            }
                        )
                        .then(res => {

                            this.filtro = false;
                            this.albaranes = res.data;
                            this.show_loading = false;

                        })
                        .catch(err => {

                            this.show_loading = false;
                            if (err.response.status != 419)
                                this.$toast.error(err.response.data.message);
                            else
                                this.$toast.error("Sesión expirada!");

                        });
                }
            });

        },

        create(){
            this.$router.push({ name: 'albaran.create'})
        },
        editItem (id) {
            this.setPagination(this.paginaActual);
            this.$router.push({ name: 'albaran.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.albaran_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/ventas/albacabs/'+this.albaran_id,{_method: 'delete'})
                .then(response => {
                this.albaranes = response.data;
                this.$toast.success('Albarán eliminado!');

            })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
  }
</script>
