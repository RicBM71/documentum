<template>
	<div v-show="show">
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :albaran="albaran"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm1>
                            <v-text-field
                                v-model="albaran.serie"
                                :error-messages="errors.collect('serie')"
                                label="Serie"
                                data-vv-name="serie"
                                data-vv-as="serie"

                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albaran.albaran"
                                :error-messages="errors.collect('albaran')"
                                label="Albarán"
                                data-vv-name="albaran"
                                data-vv-as="albarán"

                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
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
                                        :value="computedFechaAlb"
                                        label="Fecha Albarán"
                                        append-icon="event"
                                        readonly
                                        data-vv-as="Fecha Albarán"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="albaran.fecha_alb"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu2 = false"

                                    ></v-date-picker>
                                </v-menu>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albaran.factura"
                                :error-messages="errors.collect('factura')"
                                label="Factura"
                                data-vv-name="factura"
                                data-vv-as="factura"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="computedFechaFac"
                                :error-messages="errors.collect('fecha_fac')"
                                label="F. Factura"
                                data-vv-name="fecha_fac"
                                data-vv-as="F. factura"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-switch
                                v-model="albaran.notificado"
                                data-vv-name="notificado"
                                data-vv-as="Notificado"
                                label="Notificado"
                                color="primary"
                            ></v-switch>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm5>
                            <v-autocomplete
                                v-model="albaran.cliente_id"
                                v-validate="'required'"
                                data-vv-name="cliente_id"
                                data-vv-as="Cliente"
                                @change="selCliente"
                                :error-messages="errors.collect('cliente_id')"
                                :loading="loading"
                                :items="clientes"
                                :search-input.sync="search"
                                flat
                                label="Cliente"
                                required
                                ></v-autocomplete>
                        </v-flex>
                        <v-flex sm3 d-flex>
                            <v-select
                                v-model="albaran.fpago_id"
                                :error-messages="errors.collect('fpago_id')"
                                v-validate="'required'"
                                data-vv-name="fpago_id"
                                data-vv-as="Forma de pago"
                                :items="fpagos"
                                label="Forma de Pago"
                                required
                            ></v-select>
                        </v-flex>
                        <v-flex sm3 d-flex>
                            <v-select
                                v-model="albaran.vencimiento_id"
                                :error-messages="errors.collect('vencimiento_id')"
                                v-validate="'required'"
                                data-vv-name="vencimiento_id"
                                data-vv-as="Vencimiento"
                                :items="vencimientos"
                                label="Vencimiento"
                            ></v-select>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm5 d-flex>
                            <v-text-field
                                    readonly
                                    v-model="albaran.iban"
                                    label="IBAN"
                                >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albaran.username"
                                label="Usuario"
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
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm9 d-flex>
                            <v-text-field
                                    v-model="albaran.notas"
                                    label="Observaciones"
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
            'menu-ope': MenuOpe
		},
    	data () {
      		return {
                titulo:"Albaranes",
                albaran: {
                    id:0,
                    empresa_id:"",
                    ejercicio:0,
                    albaran:"",
                    serie:"",
                    fecha_alb: new Date().toISOString().substr(0, 10),
                    cliente_id:"",
                    eje_fac:"",
                    factura:"",
                    fecha_fac:null,
                    fpago_id: 1,
                    vencimiento_id:1,
                    iban:"",
                    notificado:false,
                    notas:"",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                ivas:[],
                retenciones:[],
                productos:[],
                fpagos:[],
                vencimientos: [],

                clientes:[],
                loading: false,
                search: null,


                albaran_id: "",

        		status: false,
                enviando: false,
                show: true,

                menu2:false,

      		}
        },
        mounted(){
            axios.get('/ventas/albacabs/create')
                .then(res => {
                    this.clientes = res.data.clientes;
                    this.fpagos = res.data.fpagos;
                    this.vencimientos = res.data.vencimientos;

                    this.show=true;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'albaran.index'})
                })
        },
        computed: {
            computedFechaAlb() {
                moment.locale('es');
                return this.albaran.fecha_alb ? moment(this.albaran.fecha_alb).format('L') : '';
            },
            computedFechaFac() {
                moment.locale('es');
                return this.albaran.fecha_fac ? moment(this.albaran.fecha_fac).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.albaran.updated_at ? moment(this.albaran.updated_at).format('DD/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.albaran.created_at ? moment(this.albaran.created_at).format('DD/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/ventas/albacabs";

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.post(url, this.albaran)
                            .then(response => {
                                this.$router.push({ name: 'albaran.edit', params: { id: response.data.albaran.id } })
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
            selCliente(){
               // console.log(this.albaran.cliente_id);
            }
    }
  }
</script>

<style scoped>
.v-text-field {
    padding-top: 2px;
    margin-top: 4px;
}
.v-form>.container>.layout>.flex{
    padding: 0px 8px;
}

.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
