<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm4 d-flex>
                            <v-select
                                v-model="documento.archivo_id"
                                :items="archivos"
                                v-validate="'required'"
                                data-vv-name="archivo_id"
                                data-vv-as="Archivo"
                                :error-messages="errors.collect('archivo_id')"
                                @change="loadCarpeta(documento.archivo_id)"
                                label="Archivo"
                            ></v-select>
                        </v-flex>
                        <v-flex sm4 d-flex>
                            <v-select
                                v-model="documento.carpeta_id"
                                v-validate="'required'"
                                :error-messages="errors.collect('carpeta_id')"
                                data-vv-name="carpeta_id"
                                data-vv-as="Carpeta"
                                :items="carpetas"
                                label="Carpeta"
                            ></v-select>
                        </v-flex>
                         <v-flex sm3>
                            <v-menu
                                    v-model="menu2"
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
                                        :value="computedFecha"
                                        label="Fecha"
                                        append-icon="event"
                                        readonly
                                        class="center"
                                        data-vv-as="Fecha"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="documento.fecha"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu2 = false"
                                    ></v-date-picker>
                                </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm11>
                            <v-text-field
                                v-model="documento.concepto"
                                v-validate="'required'"
                                :error-messages="errors.collect('concepto')"
                                label="Concepto"
                                data-vv-name="concepto"
                                data-vv-as="concepto"
                                required
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <v-text-field
                                v-model="documento.username"
                                :error-messages="errors.collect('username')"
                                label="Usuario"
                                data-vv-name="username"
                                readonly
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="computedFCreFormat"
                                label="Creado"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm1></v-flex>
                        <v-flex sm2>
                            <div class="text-xs-center">
                                        <v-btn @click="submit"  round  :loading="enviando" block  color="primary">
                                Guardar
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import MenuOpe from './MenuOpe'

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
        },
    	data () {
      		return {
                titulo:"Documentos",
                documento: {
                    id:0,
                    empresa_id:"",
                    archivo_id:"",
                    concepto:"",
                    fecha: new Date().toISOString().substr(0, 10),
                    cerrado: 0,
                    confidencial:0,
                    username: "",
                    updated_at:"",
                    created_at:"",
                    extracto_id:""
                },

                archivos:[],
                carpetas:[],

                documento_id: "",
                extracto: false,

                menu2: false,
        		status: false,
                enviando: false,
                show: true,
      		}
        },
        mounted(){

            this.extracto = this.$route.params.extracto;

            axios.get('/mto/documentos/create')
                .then(res => {

                    this.archivos = res.data.archivos;
                    this.carpetas = res.data.carpetas;

                    if (this.extracto != false){
                        this.documento.fecha = this.extracto.fecha;
                        this.documento.concepto = this.extracto.concepto;
                    }

                    this.show=true;
                })
                .catch(err => {

                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'documento.index'})
                })
        },
        computed: {
            computedFecha() {
                moment.locale('es');
                return this.documento.fecha ? moment(this.documento.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.documento.updated_at ? moment(this.documento.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.documento.created_at ? moment(this.documento.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/mto/documentos";

                if (this.extracto != false)
                    this.documento.extracto_id = this.extracto.id;

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.post(url, this.documento)
                            .then(response => {
                                this.$router.push({ name: 'documento.edit', params: { id: response.data.documento.id } })
                                this.enviando = false;

                            })
                            .catch(err => {
                                if (err.request.status == 422){ // fallo de validated.
                                    const msg_valid = err.response.data.errors;
                                    for (const prop in msg_valid) {
                                        // this.$toast.error(`${msg_valid[prop]}`);
                                        //console.log(prop);
                                        this.errors.add({
                                            field: prop,
                                            msg: `${msg_valid[prop]}`
                                        })
                                    }
                                }else{
                                    this.$toast.error(err.response.data.message);
                                }
                                this.enviando = false;
                            });
                        }
                    else{
                        this.enviando = false;
                    }
                });

            },
            loadCarpeta(archivo_id){

                axios.get('/mto/carpetas/'+archivo_id)
                    .then(res => {

                        this.carpetas = res.data.carpetas;
                        this.documento.carpeta_id = "";
                    })
                    .catch(err => {
                        if (err.response.status == 404)
                            this.$toast.error("Error al recargar carpetas!");
                        else
                            this.$toast.error(err.response.data.message);
                    })
            }


    }
  }
</script>
