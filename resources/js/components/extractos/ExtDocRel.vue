<template>
    <div>
        <loading :show_loading="show_loading"></loading>

        <div>


            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                v-show="computedDocs"
                                color="white"
                                icon
                                @click="attachDoc()"
                            >
                                <v-icon color="success">call_merge</v-icon>
                            </v-btn>
                        </template>
                        <span>Enlazar documentos</span>
                    </v-tooltip>
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
                            <v-flex xs1></v-flex>
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
                            <v-flex xs2 d-flex>
                                <v-select
                                    v-model="dh"
                                    :items="tipos"
                                    label="D/H"
                                ></v-select>
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
                        <v-flex xs5>
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="search"
                                label="Buscar extracto"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs2></v-flex>
                         <v-flex xs5>
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="doc_search"
                                append-icon="search"
                                label="Buscar documento"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <br/>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-data-table
                            v-model="selected"
                            :headers="headers"
                            :items="apuntes"
                            :pagination.sync="pagination"
                            :search="search"
                            rows-per-page-text="Registros por página"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr :active="props.selected" @click="props.selected = !props.selected">
                                        <td>
                                            <v-checkbox
                                                :input-value="props.selected"
                                                primary
                                                hide-details
                                            ></v-checkbox>
                                        </td>
                                        <td>{{ formatDate(props.item.fecha) }}</td>
                                        <td>{{ props.item.dh }}</td>
                                        <td>{{ props.item.concepto }} <p v-if="props.item.nota != ''"><span class='font-italic black--text'><span class="lime accent-2">{{ props.item.nota }}</span></span></p></td>
                                        <td>{{ props.item.importe | currency('€', 2, { thousandsSeparator:'.', thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false })}}</td>
                                     </tr>
                                </template>
                                <template slot="pageText" slot-scope="props">
                                    Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                                </template>
                            </v-data-table>
                        </v-flex>
                        <v-flex xs1></v-flex>
                        <v-flex xs5>
                            <v-data-table
                            v-model="doc_selected"
                            :headers="doc_headers"
                            :pagination.sync="doc_pagination"
                            :items="documentos"
                            :search="doc_search"
                            rows-per-page-text="Registros por página"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr :active="props.selected" @click="props.selected = !props.selected">
                                        <td>
                                        <v-checkbox
                                            :input-value="props.selected"
                                            primary
                                            hide-details
                                        ></v-checkbox>
                                        </td>
                                        <td>{{ formatDate(props.item.fecha) }}</td>
                                        <td>{{ props.item.concepto }}</td>
                                     </tr>
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
        titulo:"Enlazar extracto-documento",
        pagination:{
            sortBy: "fecha",
            descending: true,
        },
        search:"",
        headers: [
            {
                text: 'S',
            align: 'left',
            sortable: false
          },
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
          }
        ],
        selected: [],
        doc_search:"",
        doc_pagination:{
            sortBy: "fecha",
            descending: true,
        },
        doc_headers: [
          {
            text: 'S',
            align: 'left',
            sortable: false
          },
          {
            text: 'Fecha',
            align: 'left',
            value: 'fecha'
          },
          {
            text: 'Concepto',
            align: 'left',
            value: 'concepto'
          },
        ],
        doc_selected: [],
        editedIndex: 0,
        apuntes:[],
        documentos: [],
        status: false,
		registros: false,
        dialog: false,
        extracto_id: 0,
        documento_id: 0,

        tipos: [
                {text:'Debe','value':'D'},
                {text:'Haber','value':'H'},
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

        id_ext_sel: [],
        id_doc_sel: []
      }
    },
    mounted()
    {

        if (!this.hasDocumenta){
            this.$toast.error("No autorizado!");
            this.$router.push({ name: 'dash' })
        }

        this.show_loading = true;

        axios.get('/mto/extractos')
            .then(res => {

                this.apuntes = res.data;
                this.show_loading = false;
            })
            .catch(err =>{

                this.show_loading = false;
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })

            })

        axios.get('/mto/documentos')
            .then(res => {

                this.documentos = res.data.documentos;
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
                'hasBorraDoc'
		]),
        computedDocs(){

            return (this.selected.length > 0 && this.doc_selected.length > 0) ? true : false;
        },
        computedFechaD() {
            moment.locale('es');
            return this.fecha_d ? moment(this.fecha_d).format('MM-YYYY') : '';
        },
        computedFechaH() {
            moment.locale('es');
            return this.fecha_h ? moment(this.fecha_h).format('MM-YYYY') : '';
        },
    },
    watch: {
        apuntes: function() {
            (this.apuntes.length > 0 && this.documentos.length > 0) ? this.show_loading = false : this.show_loading = true;
        },
        documentos: function() {
            (this.apuntes.length > 0 && this.documentos.length > 0) ? this.show_loading = false : this.show_loading = true;
        }
    },
    methods:{
        formatDate(f){
            if (f == null) return null;
                moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        attachDoc(){


            var ext = [];
            this.selected.forEach(element => {
                ext.push(element.id)
            });
            var doc = [];
            this.doc_selected.forEach(element => {
                this.documento_id = element.id;
                doc.push(element.id)
            });

            this.show_loading = true;
            axios.post('/mto/documentos/attach',
                    {
                        extractos: ext,
                        documentos: doc
                    }
                )
                .then(res => {
                    this.show_loading = false;
                    this.filtro = false;
                    this.$toast.success("Apunte enlazado correctamente!");

                    this.$router.push({ name: 'documento.edit', params: { id: this.documento_id} })

                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);


                });

        },
        filtrar(){

            this.show_loading = true;
            this.apuntes = [];
            axios.post('/mto/extractos/filtrar',
                    {
                        fecha_d: this.fecha_d,
                        fecha_h: this.fecha_h,
                        dh: this.dh,
                        docu: 'T',
                        validado: 'T',
                        concepto: this.search
                    }
                )
                .then(res => {
                    this.apuntes = res.data;
                });
            axios.post('/mto/documentos/filtrar',
                    {
                        fecha_d: this.fecha_d,
                        fecha_h: this.fecha_h,
                        archivo_id: '',
                        concepto: this.doc_search
                    }
                )
                .then(res => {
                    this.documentos = res.data.documentos;
                })

            this.loading = true;

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

        }
    }
  }
</script>
