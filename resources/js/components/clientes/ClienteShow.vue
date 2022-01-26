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
                                        label="Nombre"
                                        data-vv-name="nombre"
                                        data-vv-as="nombre"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.razon"
                                        label="Razon"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.cif"
                                        label="CIF"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.telefono1"
                                        label="Teléfono"
                                        mask="### ### ###"
                                        disabled
                                        >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.telefono2"
                                        label="Teléfono"
                                        mask="### ### ###"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.direccion"
                                        label="Dirección"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm1>
                                    <v-text-field
                                        v-model="cliente.cpostal"
                                        label="CP"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.poblacion"
                                        label="Población"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.provincia"
                                        label="Provincia"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        v-model="cliente.tfmovil"
                                        label="Tf. Móvil"
                                        mask="### ### ###"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.contacto"
                                        label="Contacto"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.email"
                                        label="email"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.web"
                                        label="Web"
                                        disabled
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
                                    readonly
                                    ></v-select>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.patron"
                                        label="Patrón N.43"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3 d-flex>
                                    <v-select
                                        v-model="cliente.fpago_id"
                                        :items="fpagos"
                                        label="Forma de Pago"
                                        readonly
                                    ></v-select>
                                </v-flex>
                                <v-flex sm2>
                                    <v-text-field
                                        slot="activator"
                                        :value="computedFechaBaja"
                                        label="Fecha Baja"
                                        readonly
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.iban"
                                        label="IBAN"
                                        mask="AA## #### #### #### #### ####"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.bic"
                                        label="BIC"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm3>
                                    <v-text-field
                                        v-model="cliente.ref19"
                                        label="Ref. Recibos N.19"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm2>
                                    <v-select
                                        v-model="cliente.factura"
                                        :items="sino"
                                        label="Factura"
                                        readonly
                                    ></v-select>
                                </v-flex>

                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.efact"
                                        label="Sitio"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="cliente.eusr"
                                        label="Usuario"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        label="Password"
                                        disabled
                                    >
                                    </v-text-field>
                                </v-flex>

                            </v-layout>
                            <v-layout row wrap>
                                <v-flex sm12>
                                    <v-text-field
                                        v-model="cliente.notas1"
                                        label="Notas"
                                        disabled
                                    >
                                    </v-text-field>
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
                axios.get('/mto/clientes/'+id)
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
        },
  }
</script>
