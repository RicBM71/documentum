<template>
	<div v-show="show">
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="carpeta.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                 <v-container>
                    <v-layout row wrap>
                        <v-flex sm5 d-flex>
                            <v-autocomplete
                                v-model="carpeta.archivo_id"
                                v-validate="'required'"
                                data-vv-name="archivo_id"
                                data-vv-as="archivo"
                                :error-messages="errors.collect('archivo_id')"
                                :items="archivos"
                                flat
                                label="Archivo"
                                required
                            >
                            </v-autocomplete>
                        </v-flex>
                        <v-flex sm5>
                            <v-text-field
                                v-model="carpeta.nombre"
                                v-validate="'required'"
                                :error-messages="errors.collect('nombre')"
                                label="Nombre"
                                data-vv-name="nombre"
                                data-vv-as="nombre"
                                required
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm6>
                            <v-text-field
                                v-model="carpeta.patron"
                                :error-messages="errors.collect('patron')"
                                hint="Patrón búsqueda N.43"
                                label="Patrón"
                                data-vv-name="patron"
                                data-vv-as="Patrón"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-switch
                                v-model="carpeta.activa"
                                color="primary"
                                label="Activa"
                            ></v-switch>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <v-text-field
                                v-model="carpeta.color"
                                :error-messages="errors.collect('color')"
                                hint="red--text text--darken-1"
                                label="Color"
                                data-vv-name="color"
                                data-vv-as="Color"
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-flex>
                         <v-flex sm3>
                            <v-text-field
                                v-model="carpeta.path"
                                :error-messages="errors.collect('path')"
                                label="Path"
                                data-vv-name="path"
                                data-vv-as="path"
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
                        <v-flex sm5>
                        </v-flex>
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
import Loading from '@/components/shared/Loading'

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
                titulo:"Carpetas",
                carpeta: {
                    id:       0,
                    empresa_id: 0,
                    archivo_id:"",
                    nombre:  "",
                    path: "",
                    color: "",
                    patron:"",
                    updated_at:"",
                    created_at:"",
                },
                carpeta_id: "",
                archivos: [],

        		status: false,
                enviando: false,

                show: false,
                show_loading: true
      		}
        },
        mounted(){
            axios.get('/mto/carpetas/create')
                .then(res => {
                    this.show = true;
                    this.archivos = res.data.archivos;
                    this.show_loading = false;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'carpeta.index'})
                })
        },

        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.carpeta.updated_at ? moment(this.carpeta.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.carpeta.created_at ? moment(this.carpeta.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/mto/carpetas";
                var metodo = "post";

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios({
                            method: metodo,
                            url: url,
                            data:
                                {
                                    nombre: this.carpeta.nombre,
                                    archivo_id: this.carpeta.archivo_id,
                                    color: this.carpeta.color,
                                    path: this.carpeta.path,
                                    patron: this.carpeta.patron

                                }
                            })
                            .then(response => {

                                this.$toast.success(response.data.message);

                                this.enviando = false;
                                this.$router.push({ name: 'carpeta.edit', params: { id: response.data.carpeta.id } })
                            })
                            .catch(err => {

                                if (err.request.status == 422){ // fallo de validated.
                                    const msg_valid = err.response.data.errors;
                                    for (const prop in msg_valid) {
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

    }
  }
</script>
