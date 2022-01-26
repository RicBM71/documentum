<template>
    <div>
        <v-layout row wrap>
            <v-flex sm3 xs12>
                 <v-btn round block large color="grey" class="blue-grey lighten-3" @click="goAlbaranes()">
                     Albaranes
                     <v-icon right dark>create_new_folder</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex sm3 xs12>
                <v-btn
                    round block large color="grey" class="blue-grey lighten-3"
                    @click="goClientes()">
                        Clientes-Proveedores
                    <v-icon right dark>group</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex sm3 xs12>
                <v-btn
                    round block large color="grey" class="blue-grey lighten-3"
                    @click="goProductos()">
                    Productos
                    <v-icon right dark>local_offer</v-icon>
                 </v-btn>
            </v-flex>

        </v-layout>
        <v-layout row wrap>
            <v-flex sm3 xs12>
                 <v-btn round block large color="grey" class="blue-grey lighten-3"
                     @click="goDocumentos()">
                     Documentos
                     <v-icon right dark>save_alt</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex sm3 xs12>
                 <v-btn round block large color="grey" class="blue-grey lighten-3"
                     @click="goExtractos()">
                     Extracto Banco
                     <v-icon right dark>account_balance</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex sm3 xs12>
                 <v-btn round block large color="grey" class="blue-grey lighten-3"
                     @click="goDownload()">
                     Download ZIP
                     <v-icon right dark>get_app</v-icon>
                </v-btn>
            </v-flex>
        </v-layout>
        <v-layout row wrap v-if="isAdmin">
            <v-flex sm3 xs12>
                 <v-btn round block large color="grey" class="blue-grey lighten-3"
                     @click="goRecibos()">
                     Recibos SEPA
                     <v-icon right dark>cloud_download</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex sm3 xs12>
                 <v-btn round block large color="grey" class="blue-grey lighten-3"
                     @click="goTransfer()">
                    Remesas
                     <v-icon right dark>airplay</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex sm3 xs12>
                 <v-btn round block large color="grey" class="blue-grey lighten-3"
                     @click="goEmitTrans()">
                     Transferencias SEPA
                     <v-icon right dark>present_to_all</v-icon>
                </v-btn>
            </v-flex>
        </v-layout>
        <v-layout row wrap>
            <v-flex xs2></v-flex>
            <v-flex xs8>
                <v-responsive>
                    <v-layout column>
                        <v-img class="img-fluid" :src="logo"></v-img>
                    </v-layout>
                </v-responsive>
            </v-flex>
        </v-layout>
    </div>
</template>
<script>
import {mapActions} from "vuex";
import {mapGetters} from 'vuex';
export default {
    computed:{
        ...mapGetters([
            'isLoggedIn',
            'isRoot',
            'isAdmin',
            'empresaActiva'
		]),
    },
    data: () => ({
        logo: ""
    }),
    mounted(){

        axios.get('/dash')
            .then(res => {
                this.setAuthUser(res.data.user);
               // console.log(res.data.user);
               // console.log(res.data.user.permisos.indexOf('Documenta') >= 0);
                this.logo = "/storage/logos/"+this.empresaActiva+".png";
            })
            .catch(err => {
                console.log(err);
        })

    },
    watch: {
        empresaActiva: function (newValue) {

            this.logo = "/storage/logos/"+newValue+".png";
        }
    },
    methods:{
        ...mapActions([
				'setAuthUser'
            ]),
        goAlbaranes(){
            this.$router.push({ name: 'albaran.index' })
        },
        goClientes(){
            this.$router.push({ name: 'cliente.index' })
        },
        goProductos(){
            this.$router.push({ name: 'producto.index' })
        },
        goDocumentos(){
            this.$router.push({ name: 'documento.index' })
        },
        goExtractos(){
            this.$router.push({ name: 'extracto.index' })
        },
        goDownload(){
            this.$router.push({ name: 'documento.zip' })
        },
        goRecibos(){
            this.$router.push({ name: 'adeudo.index' })
        },
        goTransfer(){
            this.$router.push({ name: 'remesa.index' })
        },
        goEmitTrans(){
            this.$router.push({ name: 'sepa.transfer' })
        },
    }
  }
</script>
