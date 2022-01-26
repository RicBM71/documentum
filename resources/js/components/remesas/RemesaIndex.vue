<template>
    <div>
        <loading :show_loading="show_loading"></loading>

        <div v-if="registros">
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
                    <menu-ope></menu-ope>
                </v-card-title>
            </v-card>
            <v-card v-show="filtro">
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs3 d-flex>
                                <v-select
                                    v-model="cliente_id"
                                    :items="clientes"
                                    label="Cliente"
                                ></v-select>
                            </v-flex>
                            <v-flex sm2>
                                 <v-menu
                                    ref="menu_d"
                                    v-model="menu_d"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="fecha_d"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFechaD"
                                            label="Desde"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                    v-model="fecha_d"
                                    type="month"
                                    locale="es"
                                    no-title
                                    scrollable
                                    >
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="menu_d = false">Cancelar</v-btn>
                                    <v-btn flat color="primary" @click="$refs.menu_d.save(fecha_d)">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex sm2>
                                <v-menu
                                    ref="menu_h"
                                    v-model="menu_h"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="fecha_h"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFechaH"
                                            label="Hasta"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                    v-model="fecha_h"
                                    type="month"
                                    locale="es"
                                    no-title
                                    scrollable
                                    >
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="menu_h = false">Cancelar</v-btn>
                                    <v-btn flat color="primary" @click="$refs.menu_h.save(fecha_h)">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex sm3>
                                <v-text-field
                                    v-model="concepto"
                                    label="Texto a buscar"
                                    hint="No tiene en cuenta fechas"
                                    v-on:keyup.enter="filtrar"
                                ></v-text-field>
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
                            :headers="headers"
                            :items="remesas"
                            @update:pagination="updateEventPagina"
                            :pagination.sync="pagination"
                            :search="search"
                            rows-per-page-text="Registros por página"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ formatDate(props.item.fecha) }}</td>
                                    <td v-if="props.item.tipo =='A'">Recibo</td>
                                    <td v-else>Transferencia</td>
                                    <td>{{props.item.cliente.nombre}}</td>
                                    <td>{{props.item.concepto}}</td>
                                    <td class="text-xs-right">{{ props.item.importe| currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false })}}</td>
                                    <td>
                                        <v-icon
                                            v-if="props.item.enviada"
                                            color="primary"
                                            small
                                        >

                                            lock
                                        </v-icon>
                                        <v-icon
                                            v-else
                                            color="red"
                                            small
                                        >
                                            touch_app
                                        </v-icon>
                                    </td>
                                    <td class="justify-center layout px-0">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-icon
                                                    v-on="on"
                                                    small
                                                    class="mr-2"
                                                    @click="editItem(props.item.id)"
                                                >
                                                    edit
                                                </v-icon>
                                            </template>
                                            <span>Ir a remesa</span>
                                        </v-tooltip>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-icon
                                                    v-on="on"
                                                    v-show="isAdmin && !props.item.enviada"
                                                    small
                                                    @click="openDialog(props.item)"
                                                >
                                                delete
                                                </v-icon>
                                            </template>
                                            <span>Borrar Transferencia</span>
                                        </v-tooltip>

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
    </div>
</template>
<script>
import Loading from '@/components/shared/Loading'
import MyDialog from '@/components/shared/MyDialog'
import moment from 'moment';
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";

  export default {
    $_veeValidate: {
        validator: 'new'
    },
    components: {
        'menu-ope': MenuOpe,
        'my-dialog': MyDialog,
        'loading': Loading
    },
    data () {
      return {
        titulo:"Remesas",
        search:"",
        paginaActual:{},
        pagination:{
            model: "remesas",
            descending: true,
            page: 1,
            rowsPerPage: 10,
            sortBy: "fecha",
            search: ""
        },
        headers: [
          {
            text: 'Fecha',
            align: 'left',
            value: 'fecha'
          },
          {
            text: 'Tipo',
            align: 'left',
            value: 'tipo'
          },
          {
            text: 'Deudor/Beneficiario',
            align: 'left',
            value: 'cliente.nombre'
          },
          {
            text: 'Concepto',
            align: 'left',
            value: 'concepto'
          },
          {
            text: 'Importe',
            align: 'center',
            value: 'importe'
          },
          {
            text: 'Enviada',
            align: 'left',
            value: 'enviada'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        remesas:[],
        clientes:[],

        remesa_id: 0,
        status: false,
		registros: false,
        dialog: false,
        concepto:"",

        cliente_id:0,
        index: 0,
        show_loading: false,
        filtro: false,

        menu_d: false,
        menu_h: false,
        fecha_d: new Date().toISOString().substr(0, 5)+"01",
        fecha_h: new Date().toISOString().substr(0, 7),

      }
    },
    mounted()
    {
        if (this.getPagination.model == this.pagination.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        this.show_loading = true;
        axios.get('/mto/remesa')
            .then(res => {
                //console.log(res);
                this.remesas = res.data.remesas;

                this.clientes = res.data.clientes;
                this.registros = true;

                this.show_loading = false;
            })
            .catch(err =>{

                this.show_loading = false;

                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })

            })
    },
    computed:{
        ...mapGetters([
            'isAdmin',
            'getPagination',
        ]),
        computedFechaD() {
            moment.locale('es');
            return this.fecha_d ? moment(this.fecha_d).format('MM-YYYY') : '';
        },
        computedFechaH() {
            moment.locale('es');
            return this.fecha_h ? moment(this.fecha_h).format('MM-YYYY') : '';
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
        filtrar(){

            this.show_loading = true;
            axios.post('mto/remesa/filtrar',
                    {
                        fecha_d: this.fecha_d,
                        fecha_h: this.fecha_h,
                        cliente_id: this.cliente_id,
                        concepto: this.concepto
                    }
                )
                .then(res => {
                //    console.log(res);
                    this.pagination.page = 1;
                    this.filtro = false;
                    this.remesa = res.data.remesa;
                    this.show_loading = false;

                })
                .catch(err => {

                    this.show_loading = false;
                    this.$toast.error(err.response.data.message);


                });
        },
        openDialog (item){

            this.dialog = true;
            this.remesa_id = item.id;
            this.index = this.remesas.indexOf(item)
        },
        destroyReg () {
            this.dialog = false;
            //console.log(this.remesa_id)
            axios.post('/mto/remesa/'+this.remesa_id,{_method: 'delete'})
                .then(response => {

                this.remesas.splice(this.index, 1)
                this.$toast.success('Transferencia eliminada!');


            })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
        editItem (id) {

            this.setPagination(this.paginaActual);
            if (this.isAdmin)
                this.$router.push({ name: 'remesa.edit', params: { id: id } })
            else
                this.$router.push({ name: 'remesa.show', params: { id: id } })
        },
    }
  }
</script>
