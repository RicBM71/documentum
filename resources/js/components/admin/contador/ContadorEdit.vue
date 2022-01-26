<template>
	<div v-show="show">
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="contador.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <v-text-field
                                v-model="contador.ejercicio"
                                v-validate="'required|integer|max_value:2050'"
                                :error-messages="errors.collect('ejercicio')"
                                label="Ejercicio"
                                data-vv-name="ejercicio"
                                data-vv-as="ejercicio"
                                required
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm2>
                            <v-text-field
                                v-model="contador.seriealb"
                                v-validate="'required|max:3'"
                                :error-messages="errors.collect('seriealb')"
                                label="Serie Albarán"
                                data-vv-name="seriealb"
                                data-vv-as="Serie"
                                required
                                v-on:keyup.enter="submit"                        >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="contador.albaran"
                                v-validate="'required|numeric'"
                                :error-messages="errors.collect('albaran')"
                                label="Ult. Albarán"
                                data-vv-name="albaran"
                                data-vv-as="albarán"
                                required
                                type="number"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="contador.seriefac"
                                v-validate="'required|max:3'"
                                :error-messages="errors.collect('seriefac')"
                                label="Serie Factura"
                                data-vv-name="seriefac"
                                data-vv-as="Serie"
                                required
                                v-on:keyup.enter="submit"                        >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="contador.factura"
                                v-validate="'required|numeric'"
                                :error-messages="errors.collect('factura')"
                                label="Ult. Factura"
                                data-vv-name="factura"
                                data-vv-as="factura"
                                required
                                type="number"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="contador.serieabo"
                                v-validate="'required|max:3'"
                                :error-messages="errors.collect('serieabo')"
                                label="Serie Abonos"
                                data-vv-name="serieabo"
                                data-vv-as="Serie"
                                required
                                v-on:keyup.enter="submit"                        >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="contador.abono"
                                v-validate="'required|numeric'"
                                :error-messages="errors.collect('abono')"
                                label="Ult. Abono"
                                data-vv-name="abono"
                                data-vv-as="abono"
                                required
                                type="number"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm2>
                            <v-text-field
                                v-model="contador.username"
                                :error-messages="errors.collect('username')"
                                label="Usuario"
                                data-vv-name="username"
                                readonly
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
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
                        <v-flex sm3>
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
                titulo:"Contadores",
                contador: {
                    id:       0,
                    ejercicio:  "",
                    albaran: 0,
                    seriealb: "",
                    factura: 0,
                    seriefac: "",
                    abono: 0,
                    serieabo: "",
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

            if (id > 0)
                axios.get('/admin/contadors/'+id+'/edit')
                    .then(res => {
                        this.contador = res.data.contador;
                        this.show = true;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'contador.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.contador.updated_at ? moment(this.contador.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.contador.created_at ? moment(this.contador.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/admin/contadors/"+this.contador.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.contador)
                            .then(response => {
                                console.log(response);
                                this.$toast.success(response.data.message);
                                this.contador = response.data.contador;
                                this.enviando = false;
                            })
                            .catch(err => {
                                //console.log(err.response.data.errors);
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

    }
  }
</script>
<style scoped>
