<template>
    <div>
        <loading :show_loading="show_loading"></loading>

        <div v-if="registros">

            <v-dialog v-model="dialog" max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Editar</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field
                                    ref="myText"
                                    v-model="editedItem.concepto"
                                    v-on:keyup.enter="save"
                                    label="Concepto">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                         <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field
                                    ref="myText"
                                    v-model="editedItem.nota"
                                    v-on:keyup.enter="save"
                                    label="Nota">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="close">Cancelar</v-btn>
                        <v-btn color="blue darken-1" flat @click="save">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <menu-ope :fecha="fecha_d"></menu-ope>
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
                        <span>Filtros</span>
                    </v-tooltip>
                </v-card-title>
            </v-card>
            <v-card v-show="filtro">
                <v-form>
                    <v-container>
                        <v-layout row wrap>
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
                            <v-flex xs1 d-flex>
                                <v-select
                                    v-model="dh"
                                    :items="tipos"
                                    label="D/H"
                                ></v-select>
                            </v-flex>
                             <v-flex xs2 d-flex>
                                <v-select
                                    v-model="docu"
                                    :items="documentos"
                                    label="Documentos"
                                ></v-select>
                            </v-flex>
                            <v-flex xs2 d-flex>
                                <v-select
                                    v-model="validado"
                                    :items="validados"
                                    label="Validados"
                                ></v-select>
                            </v-flex>
                            <v-flex sm2>
                                <v-text-field
                                    v-model="concepto"
                                    label="Texto a buscar"
                                    hint="No tiene en cuenta fechas"
                                    v-on:keyup.enter="filtrar"
                                ></v-text-field>
                            </v-flex>
                            <v-spacer></v-spacer>
                            <v-flex sm1>
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
                            :expand="expand"
                            :headers="headers"
                            :items="apuntes"
                            :search="search"
                            @update:pagination="updateEventPagina"
                            :pagination.sync="pagination"
                            rows-per-page-text="Registros por página"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr>
                                        <td>{{ formatDate(props.item.fecha) }}</td>
                                        <td>{{ props.item.dh }}</td>
                                        <td>{{ props.item.concepto }} <p v-if="props.item.nota != ''"><span class='font-italic black--text'><span class="lime accent-2">{{ props.item.nota }}</span></span></p></td>
                                        <td :class=colorLin(props.item.dh)>{{ props.item.importe | currency('€', 2, { thousandsSeparator:'.', thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false })}}</td>
                                        <td class="justify-center layout px-0">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon
                                                        v-on="on"
                                                        v-show="hasDocumenta && !props.item.validado"
                                                        small
                                                        class="mr-2"
                                                        @click="editNota(props.item)"
                                                        color="grey"
                                                    >
                                                        textsms
                                                    </v-icon>
                                                </template>
                                                <span>Editar apute/nota</span>
                                            </v-tooltip>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon v-show="props.item.documentos.length > 0"
                                                        v-on="on"
                                                        class="mr-2"
                                                        @click="props.expanded = !props.expanded"
                                                        color="blue lighten-1"
                                                    >
                                                        attach_file
                                                    </v-icon>
                                                </template>
                                                <span>Ver Documento</span>
                                            </v-tooltip>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon v-show="!props.item.documentos.length > 0 && hasDocumenta && !props.item.validado"
                                                        v-on="on"
                                                        small
                                                        :color="computedColor(props.item)"
                                                        class="mr-2"
                                                        @click="createDocu(props.item)"
                                                    >
                                                        save
                                                    </v-icon>
                                                </template>
                                                <span>Crear Documento</span>
                                            </v-tooltip>
                                             <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon
                                                        v-on="on"
                                                        v-show="hasDocumenta && !props.item.validado"
                                                        small
                                                        class="mr-2"
                                                        @click="bloquear(props.item)"
                                                        color="orange"
                                                    >
                                                        lock
                                                    </v-icon>
                                                </template>
                                                <span>Validar apunte</span>
                                            </v-tooltip>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon
                                                        v-on="on"
                                                        v-show="hasDocumenta && props.item.validado"
                                                        small
                                                        class="mr-2"
                                                        @click="liberar(props.item)"
                                                        color="green"
                                                    >
                                                        done
                                                    </v-icon>
                                                </template>
                                                <span>Liberar apunte</span>
                                            </v-tooltip>
                                        </td>
                                     </tr>
                                </template>
                                <template v-slot:expand="props">
                                    <v-card flat>
                                        <v-card-text :key="child.id" v-for="child in props.item.documentos" style="border-bottom: 1px solid black">



                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn
                                                        v-on="on"
                                                        color="white"
                                                        icon
                                                        @click="editDoc(child.id)"
                                                    >
                                                        <v-icon :color="estaCerrado(child.cerrado)">zoom_out_map</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Ver documento</span>
                                            </v-tooltip>

                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn
                                                        v-show="hasDocumenta && hasBorraDoc"
                                                        v-on="on"
                                                        color="white"
                                                        icon
                                                        @click="detachDoc(child)"
                                                    >
                                                        <v-icon>delete_outline</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Eliminar relación</span>
                                            </v-tooltip>

                                            <span :class="child.archivo.color">{{child.archivo.nombre}}</span>-
                                            <span :class="child.carpeta.color">{{child.carpeta.nombre}}</span>:
                                            <span :class="child.carpeta.color">{{formatDate(child.fecha)+" "+child.concepto}}</span>

                                        </v-card-text>
                                    </v-card>

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
import moment from 'moment';
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex';
import {mapActions} from "vuex";

  export default {
    $_veeValidate: {
      		validator: 'new'
    },
    components: {
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo:"Extracto",
        paginaActual:{},
        pagination:{
            model: "extracto",
            descending: true,
            page: 1,
            rowsPerPage: 10,
            sortBy: "fecha",
            search: ""
        },
        search:"",
        headers: [
          {
            text: 'Fecha',
            align: 'left',
            value: 'fecha'
          },
          {
            text: 'M',
            align: 'left',
            value: 'dh'
          },
          {
            text: 'Concepto',
            align: 'left',
            value: 'concepto'
          },
          {
            text: 'Importe',
            align: 'left',
            value: 'importe'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: 'nota'
          }
        ],
        editedIndex: 0,
        editedItem: {
            'id' : "",
            empresa_id: "",
            cuenta_id: "",
            fecha: "",
            dh: "",
            concepto: "",
            nota: "",
            importe: "",
            validado: false,
            username:"",
            created_at:"",
            updated_at:"",
        },
        apuntes:[],
        status: false,
		registros: false,
        dialog: false,
        extracto_id: 0,
        documento_id: 0,
        expand: true,
        concepto: "",

        tipos: [
                {text:'Debe','value':'D'},
                {text:'Haber','value':'H'},
                {text:'Todos','value':'T'}
            ],
        documentos: [
                {text:'Con documentos','value':'S'},
                {text:'Sin documentos','value':'N'},
                {text:'Todos','value':'T'}
            ],

        validados: [
                {text:'Si','value':'1'},
                {text:'No','value':'0'},
                {text:'Todos','value':'T'}
            ],

        validado: 'T',
        docu: "T",
        show_loading: false,
        filtro: false,

        menu_d: false,
        menu_h: false,
        fecha_d: new Date().toISOString().substr(0, 5)+"01",
        fecha_h: new Date().toISOString().substr(0, 7),
        dh:"T",

        nota: "",
      }
    },
    mounted()
    {

        this.show_loading = true;
        if (this.getPagination.model == this.pagination.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        axios.get('/mto/extractos')
            .then(res => {
                //console.log(res.data);
                this.apuntes = res.data;
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
                'hasDocumenta',
                'isAdmin',
                'getPagination',
                'hasBorraDoc'
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
        computedColor(item){
            if (item.documentos.length > 0 || item.nota > '')
                return 'primary';
            else
                return 'orange';
        },
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
        colorLin(dh){
            if (dh == 'D')
                return "text-xs-right red--text text--accent-4";
            else
                return "text-xs-right indigo--black";
        },
        formatDate(f){
            if (f == null) return null;
                moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        estaCerrado(cerrado){
            if (cerrado) return ''; else return 'warning';
        },
        filtrar(){

            this.$validator.validateAll().then((result) => {
                    if (result){
                        this.show_loading = true;
                        axios.post('mto/extractos/filtrar',
                                {
                                    fecha_d: this.fecha_d,
                                    fecha_h: this.fecha_h,
                                    dh: this.dh,
                                    docu: this.docu,
                                    validado: this.validado,
                                    concepto: this.concepto
                                }
                            )
                            .then(res => {
                                //console.log(res);
                                this.pagination.page = 1;
                                this.filtro = false;

                                this.apuntes = res.data;
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
        editDoc (id) {
            this.setPagination(this.paginaActual);

            if (this.hasDocumenta)
                this.$router.push({ name: 'documento.edit', params: { id: id } })
            else
                this.$router.push({ name: 'documento.show', params: { id: id } })
        },
        createDocu(extracto){
            this.setPagination(this.paginaActual);

            if (this.documento_id == 0)
                this.$router.push({ name: 'documento.create', params: { extracto: extracto } })
            else{
                this.attachDoc(extracto.id);
            }
        },
        detachDoc(child){

            this.show_loading = true;
            axios.post('mto/documentos/'+child.pivot.documento_id+'/detach',
                    {
                        extracto_id: child.pivot.extracto_id
                    }
                )
                .then(res => {
                    this.filtro = false;

                    this.filtrar();
                    this.show_loading = false;

                })
                .catch(err => {

                    this.show_loading = false;
                    this.$toast.error(err.response.data.message);


                });

        },editNota(item){

            this.editedIndex = this.apuntes.indexOf(item)
            this.editedItem = Object.assign({}, item)

            this.$nextTick(() => this.$refs.myText.focus())

            this.dialog = true;
        },
        close(){
            this.dialog = false;
        },
        save(){
            this.dialog = false;

            Object.assign(this.apuntes[this.editedIndex], this.editedItem)

            var url = "/mto/extractos/"+this.editedItem.id;
            axios.put(url, {nota: this.editedItem.nota,
                            concepto: this.editedItem.concepto})
                .then(res => {

                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                });
        },
        liberar(item){

            this.editedIndex = this.apuntes.indexOf(item)

            var url = "/mto/extractos/"+item.id+"/liberar";
            axios.put(url, {validado: false})
                .then(res => {
                    Object.assign(this.apuntes[this.editedIndex], res.data.extracto)

                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                });
        },
        bloquear(item){

            this.editedIndex = this.apuntes.indexOf(item)

            var url = "/mto/extractos/"+item.id+"/liberar";
            axios.put(url, {validado: true})
                .then(res => {
                    Object.assign(this.apuntes[this.editedIndex], res.data.extracto)

                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                });
        }



    }
  }
</script>
