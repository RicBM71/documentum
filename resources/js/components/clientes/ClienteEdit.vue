<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="cliente.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-tabs fixed-tabs>
                <v-tab>
                        Datos generales
                </v-tab>
                <v-tab>
                        Albaranes
                </v-tab>
                <v-tab>
                        Documentos
                </v-tab>
                <v-tab-item>
                    <v-form>
                        <v-container>
                            <v-layout row wrap>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.nombre"
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
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.razon"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('razon')"
                                        label="Razon"
                                        data-vv-name="razon"
                                        required
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.cif"
                                        v-validate="'required|min:4'"
                                        :error-messages="errors.collect('cif')"
                                        label="CIF"
                                        required
                                        data-vv-name="cif"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.telefono1"
                                        :error-messages="errors.collect('telefono1')"
                                        label="Teléfono"
                                        data-vv-name="telefono1"
                                        data-vv-as="Teléfono"
                                        mask="### ### ###"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.telefono2"
                                        :error-messages="errors.collect('telefono2')"
                                        label="Teléfono"
                                        data-vv-name="telefono2"
                                        data-vv-as="Teléfono"
                                        mask="### ### ###"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.direccion"
                                        :error-messages="errors.collect('direccion')"
                                        label="Dirección"
                                        data-vv-name="direccion"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm1>
                                    <v-text-field
                                        v-model="cliente.cpostal"
                                        :error-messages="errors.collect('cpostal')"
                                        label="CP"
                                        data-vv-name="CP"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.poblacion"
                                        :error-messages="errors.collect('poblacion')"
                                        label="Población"
                                        data-vv-name="poblacion"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.provincia"
                                        :error-messages="errors.collect('provincia')"
                                        label="Provincia"
                                        data-vv-name="provincia"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.tfmovil"
                                        :error-messages="errors.collect('tfmovil')"
                                        label="Tf. Móvil"
                                        data-vv-name="tfmovil"
                                        mask="### ### ###"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.contacto"
                                        :error-messages="errors.collect('contacto')"
                                        label="Contacto"
                                        data-vv-name="contacto"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.email"
                                        v-validate="'email'"
                                        :error-messages="errors.collect('email')"
                                        label="email"
                                        data-vv-name="email"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.web"
                                        :error-messages="errors.collect('web')"
                                        label="Web"
                                        data-vv-name="web"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm4 d-flex>
                                    <v-select
                                    v-model="cliente.carpeta_id"
                                    :items="carpetas"
                                    label="Carpeta"
                                    ></v-select>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.patron"
                                        :error-messages="errors.collect('patron')"
                                        label="Patrón N.43"
                                        data-vv-name="patron"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3 d-flex>
                                    <v-select
                                    v-model="cliente.fpago_id"
                                    :items="fpagos"
                                    label="Forma de Pago"
                                    ></v-select>
                                </v-flex>
                                <v-flex sm2>
                                    <v-menu
                                            v-model="calfbaja"
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
                                                :value="computedFechaBaja"
                                                clearable
                                                label="Fecha Baja"
                                                prepend-icon="event"
                                                readonly
                                                data-vv-as="F. Baja"
                                                @click:clear="clearDate"
                                                ></v-text-field>
                                            <v-date-picker
                                                v-model="cliente.fechabaja"
                                                no-title
                                                locale="es"
                                                first-day-of-week=1
                                                @input="calfbaja = false"

                                            ></v-date-picker>
                                    </v-menu>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.iban"
                                        :error-messages="errors.collect('iban')"
                                        label="IBAN"
                                        mask="AA## #### #### #### #### ####"
                                        counter=24
                                        data-vv-name="iban"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.bic"
                                        v-validate="rules"
                                        :error-messages="errors.collect('bic')"
                                        label="BIC"
                                        counter=11
                                        data-vv-name="bic"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.ref19"
                                        v-validate="ref19"
                                        :error-messages="errors.collect('ref19')"
                                        label="Ref. Recibos N.19"
                                        data-vv-as="referencia"
                                        data-vv-name="ref19"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-select
                                        v-model="cliente.factura"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('factura')"
                                        data-vv-name="factura"
                                        data-vv-as="Factura"
                                        :items="sino"
                                        label="Factura"
                                    ></v-select>
                                </v-flex>

                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.efact"
                                        :error-messages="errors.collect('efact')"
                                        label="Sitio"
                                        data-vv-name="efact"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.eusr"
                                        :error-messages="errors.collect('eusr')"
                                        label="Usuario"
                                        data-vv-name="eusr"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.epass"
                                        :error-messages="errors.collect('epass')"
                                        label="Password"
                                        data-vv-name="epass"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>

                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm12>
                                    <v-text-field
                                        v-model="cliente.notas1"
                                        :error-messages="errors.collect('notas1')"
                                        label="Notas"
                                        data-vv-name="notas1"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>

                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.username"
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
                                <v-flex sm3></v-flex>
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
                </v-tab-item>
                <v-tab-item>
                    <alb-cliente v-if="cliente.id > 0" :cliente_id="cliente.id"></alb-cliente>
                </v-tab-item>
                <v-tab-item>
                    <docu-cli v-if="cliente.id > 0" :documentos="documentos"></docu-cli>
                </v-tab-item>
            </v-tabs>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import MenuOpe from './MenuOpe'
import AlbCliente from './ClienteAlbaran'
import DocuCli from './ClienteDocumento'

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'alb-cliente': AlbCliente,
            'docu-cli': DocuCli
		},
    	data () {
      		return {
                titulo:"Cliente/Proveedor",
                cliente: {
                    id:0,
                    empresa_id:"",
                    razon:"",
                    nombre:"",
                    direccion:"",
                    cpostal:"",
                    poblacion:"",
                    provincia:"",
                    telefono1:"",
                    telefono2:"",
                    tfmovil:"",
                    email:"",
                    contacto:"",
                    cif:"",
                    fechabaja:"",
                    web:"",
                    carpeta_id:"",
                    patron:"",
                    notas1:"",
                    efact:"",
                    eusr:"",
                    epass:"",
                    egrupo:"",
                    fpago_id:"",
                    factura:"",
                    iban:"",
                    bic:"",
                    ref19:"",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                sino:[
                    {value: 1, text:"Si"}, {value: 0, text:"No"},
                ],
                clientes:[],
                carpetas:[],
                documentos:[],

                fpagos:[],

                cliente_id: "",

        		status: false,
                enviando: false,

                calfbaja:false,
                show: false,


      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/mto/clientes/'+id+'/edit')
                    .then(res => {
                        this.documentos = res.data.documentos;
                        this.cliente = res.data.cliente;
                        this.carpetas = res.data.carpetas;
                        this.fpagos = res.data.fpagos;

                        this.show=true;
                    })
                    .catch(err => {
                        if (err.response.status == 404)
                            this.$toast.error("Cliente No encontrado!");
                        else
                            this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'cliente.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.cliente.updated_at ? moment(this.cliente.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.cliente.created_at ? moment(this.cliente.created_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFechaBaja() {
                moment.locale('es');
                return this.cliente.fechabaja ? moment(this.cliente.fechabaja).format('D/MM/YYYY') : '';
            },
            rules() {
                return this.cliente.iban > '' ? 'required' : '';
            },
            ref19() {
                return this.cliente.fpago_id == 3 ? 'required' : '';
            }
        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/mto/clientes/"+this.cliente.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.cliente)
                            .then(res => {
                               // console.log(res);
                                this.$toast.success(res.data.message);
                                this.cliente = res.data.cliente;
                                this.documentos = res.data.documentos
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
            clearDate(){
                this.user.fechabaja = null;
            },

    }
  }
</script>
