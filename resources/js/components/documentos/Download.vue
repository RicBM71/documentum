<template>
    <div>
        <loading :show_loading="show_loading"></loading>

        <div>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>

                </v-card-title>
            </v-card>
            <v-card>
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs4 d-flex>
                                <v-select
                                    v-model="archivo_id"
                                    :items="archivos"
                                    multiple
                                    label="Archivo"
                                    required
                                ></v-select>
                            </v-flex>
                            <v-flex sm1></v-flex>
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
                            <v-spacer></v-spacer>
                            <v-flex sm2>
                                <v-btn @click="zip"  round  block  color="info">
                                    Download
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-card>
        </div>
    </div>
</template>
<script>
import Loading from '@/components/shared/Loading'
import moment from 'moment';
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";

  export default {
    components: {
        'loading': Loading
    },
    data () {
      return {
        titulo:"Download documentos",
        archivos:[],
        archivo_id:"",

        show_loading: false,

        menu_d: false,
        menu_h: false,
        fecha_d: new Date().toISOString().substr(0, 5)+"01",
        fecha_h: new Date().toISOString().substr(0, 7),


      }
    },
    mounted()
    {

        this.show_loading = true;
        axios.get('/mto/documentos')
            .then(res => {
               // console.log(res);
                this.archivos = res.data.archivos;
                this.archivo_id = res.data.archi_defecto;
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
            'getPagination'
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
        zip(){

            this.show_loading = true;
            console.log(this.archivo_id);

            axios({
                method: 'post',
                url: '/mto/documentos/zip',
                responseType: 'blob',
                data: {
                    fecha_d: this.fecha_d,
                    fecha_h: this.fecha_h,
                    archivo_id: this.archivo_id
                }
                })
                .then(res => {
                    //console.log(res);
                    // let blob = new Blob([res.data]);
                    // let link = document.createElement('a');
                    // link.href = window.URL.createObjectURL(blob);
                    // link.download = 'DOCU'+new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'.zip';
                    // link.click();

                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(res.data)
                    link.download = 'DOCU'+new Date().getFullYear()+'-'+(new Date().getMonth()+1)+(new Date().getUTCMilliseconds())+'.zip'

                    document.body.appendChild(link);
                    link.click()
                    document.body.removeChild(link);

                    this.$toast.success("Descarga completada!");

                    this.show_loading = false;

                })
                .catch(err => {
                    console.log(err);
                    this.show_loading = false;
                    if (err.response.status == 404)
                        this.$toast.error('No hay documentos!');
                    else
                        this.$toast.error(err.response.data.message);
                });
        }
    }
  }
</script>
