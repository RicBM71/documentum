<template>
	<div v-show="show">

        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="archivo.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm1></v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="archivo.nombre"
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
                        <v-flex sm2>
                            <v-text-field
                                v-model="archivo.color"
                                v-validate="'required'"
                                :error-messages="errors.collect('color')"
                                label="Color"
                                data-vv-name="color"
                                data-vv-as="Color"
                                required
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                         <v-flex sm2>
                            <v-text-field
                                v-model="archivo.path"
                                v-validate="'required'"
                                :error-messages="errors.collect('path')"
                                label="Path"
                                data-vv-name="path"
                                data-vv-as="Path"
                                required
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-switch
                                v-model="archivo.docuzip"
                                color="primary"
                                label="Incluir en ZIP"
                            ></v-switch>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm1></v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
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

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
		},
    	data () {
      		return {
                titulo:"Archivo",
                archivo: {
                    id:       0,
                    nombre:  "",
                    path: "",
                    color: "",
                    docuzip: false,
                    updated_at:"",
                    created_at:"",
                },

        		status: false,
                enviando: false,

                show: false,
      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/admin/archivos/'+id+'/edit')
                    .then(res => {

                        this.archivo = res.data.archivo;
                        this.show = true;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'archivo.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.archivo.updated_at ? moment(this.archivo.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.archivo.created_at ? moment(this.archivo.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                //console.log("Edit user (submit):"+this.archivo.id);
                this.enviando = true;

                var url = "/admin/archivos/"+this.archivo.id;
                var metodo = "put";
                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios({
                            method: metodo,
                            url: url,
                            data:
                                {
                                    nombre: this.archivo.nombre,
                                    color: this.archivo.color,
                                    path: this.archivo.path,
                                    docuzip: this.archivo.docuzip

                                }
                            })
                            .then(response => {
                                //console.log(response);
                                this.$toast.success(response.data.message);
                                this.archivo = response.data.archivo;
                                this.enviando = false;
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
