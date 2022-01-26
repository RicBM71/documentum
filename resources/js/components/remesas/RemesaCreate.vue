
<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope
                    :id="remesa.id"
                    :enviada="computedEnviada">
                </menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm2 d-flex>
                            <v-select
                                v-model="remesa.tipo"
                                :items="tipos"
                                v-validate="'required'"
                                data-vv-name="tipo"
                                data-vv-as="Tipo"
                                :error-messages="errors.collect('tipo')"
                                label="Tipo"
                            ></v-select>
                        </v-flex>
                        <v-flex sm4 d-flex>
                            <v-select v-if="remesa.tipo == 'T'"
                                v-model="remesa.cliente_id"
                                :items="clientes"
                                v-validate="'required'"
                                data-vv-name="cliente_id"
                                data-vv-as="Cliente"
                                :error-messages="errors.collect('cliente_id')"
                                label="Beneficiario"
                            ></v-select>
                            <v-select v-else
                                v-model="remesa.cliente_id"
                                @change="selCli(remesa.cliente_id)"
                                :items="clientes"
                                v-validate="'required'"
                                data-vv-name="cliente_id"
                                data-vv-as="Cliente"
                                :error-messages="errors.collect('cliente_id')"
                                label="Deudor"
                            ></v-select>
                        </v-flex>
                        <v-flex sm3 d-flex>
                            <v-select v-if="remesa.tipo == 'T'"
                                v-model="remesa.categoria"
                                :items="categorias"
                                data-vv-name="categoria"
                                data-vv-as="Categoría"
                                :error-messages="errors.collect('categoria')"
                                label="Categoría"
                            ></v-select>
                            <v-text-field v-else
                                v-model="remesa.ref19"
                                readonly
                                label="Referencia"
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
                                        :value="computedFecha"
                                        label="Fecha"
                                        append-icon="event"
                                        readonly
                                        class="center"
                                        data-vv-as="Fecha"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="remesa.fecha"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu2 = false"
                                    ></v-date-picker>
                                </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm7>
                            <v-text-field
                                v-model="remesa.concepto"
                                v-validate="'required'"
                                :error-messages="errors.collect('concepto')"
                                label="Concepto"
                                data-vv-name="concepto"
                                data-vv-as="Concepto"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                         <v-flex sm2>
                             <v-text-field
                                :disabled="computedEnviada"
                                v-model="remesa.importe"
                                v-validate="'required|decimal:2'"
                                :error-messages="errors.collect('importe')"
                                label="Importe"
                                data-vv-name="importe"
                                data-vv-as="Importe"
                                required
                                class="inputPrice"
                                type="number"
                                v-on:keyup.enter="submit"
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
                titulo:"Remesas",
                remesa: {
                    id:0,
                    tipo: 'A',
                    categoria: 'SUPP',
                    empresa_id:"",
                    cliente_id:"",
                    concepto:"",
                    fecha: new Date().toISOString().substr(0, 10),
                    enviada: 0,
                    iban_cargo: "",
                    bic_cargo: "",
                    iban_abono: "",
                    bic_abono: "",
                    ref19: "",
                    importe: "",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                tipos: [
                    {value: 'A', text:"Recibo"}, {value: 'T', text:"Transferencia"},
                ],
                categorias: [
                    {value: 'SALA', text:"Salario"}, {value: 'SUPP', text:"Pago a Proveedores"}, {value:'OTHR', text: 'Otros'},
                ],

                clientes:[],

                remesa_id: "",

                menu2: false,
        		status: false,
                enviando: false,
                show: true,
      		}
        },
        mounted(){

            //this.extracto = this.$route.params.extracto;

            axios.get('/mto/remesa/create')
                .then(res => {

                    this.clientes = res.data.clientes;

                    this.show=true;
                })
                .catch(err => {

                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'remesa.index'})
                })
        },
        computed: {
            computedEnviada(){
                return this.remesa.enviada ? true : false;
            },
            computedFecha() {
                moment.locale('es');
                return this.remesa.fecha ? moment(this.remesa.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.remesa.updated_at ? moment(this.remesa.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.remesa.created_at ? moment(this.remesa.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/mto/remesa";

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.post(url, this.remesa)
                            .then(response => {

                                this.$router.push({ name: 'remesa.edit', params: { id: response.data.remesa.id } })
                                this.enviando = false;

                            })
                            .catch(err => {
                                console.log(err);
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
            selCli(id){

                let index = this.clientes.findIndex((item) => item.value === id);
                this.remesa.ref19 = this.clientes[index].ref19;

            },
            loadCarpeta(archivo_id){

                axios.get('/mto/carpetas/'+archivo_id)
                    .then(res => {

                        this.carpetas = res.data.carpetas;
                        this.remesa.carpeta_id = "";
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

<style scoped>


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
