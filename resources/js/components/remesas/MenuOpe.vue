<template>
    <div>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                v-show="isAdmin"
                    v-on="on"
                    color="white"
                    icon
                    @click="goCreate"
                >
                    <v-icon color="primary">add</v-icon>
                </v-btn>
            </template>
                <span>Nuevo</span>
        </v-tooltip>
         <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="id > 0 && !enviada && isAdmin"
                    v-on="on"
                    color="white"
                    icon
                    @click="openDialog"
                >
                    <v-icon color="primary">delete</v-icon>
                </v-btn>
            </template>
                <span>Borrar Registro</span>
        </v-tooltip>
        <v-tooltip bottom v-if="id > 0 && !enviada">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="isAdmin"
                    v-on="on"
                    color="white"
                    icon
                    @click="goBloqueo(1)"
                >
                    <v-icon color="primary">lock_open</v-icon>
                </v-btn>
            </template>
            <span>Bloquear transferencia</span>
        </v-tooltip>
        <v-tooltip bottom v-if="id > 0 && enviada">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    v-show="isAdmin"
                    color="white"
                    icon
                    @click="goBloqueo(0)"
                >
                    <v-icon color="primary">lock</v-icon>
                </v-btn>
            </template>
            <span>Desbloquear transferencia</span>
        </v-tooltip>
        <v-tooltip bottom v-if="id > 0" >
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goSepa"
                >
                    <v-icon color="primary">present_to_all</v-icon>
                </v-btn>
            </template>
            <span>Generar fichero órden SEPA</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goExtracto"
                >
                    <v-icon color="primary">account_balance</v-icon>
                </v-btn>
            </template>
            <span>Ir a Extracto Banco</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goIndex"
                >
                    <v-icon color="primary">list</v-icon>
                </v-btn>
            </template>
            <span>Lista</span>
        </v-tooltip>

    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import {mapGetters} from 'vuex';
export default {
    props:{
        id: Number,
        enviada: Boolean,
        tipo: String
    },
    components: {
        'my-dialog': MyDialog
    },
    data () {
      return {
          dialog: false
      }
    },
    computed: {
        ...mapGetters([
            'isAdmin'
        ])
    },
    methods:{
        goCreate(){
            this.$router.push({ name: 'remesa.create', params: { extracto: false }})
        },
        goIndex(){
            this.$router.push({ name: 'remesa.index' })
        },
        goExtracto(){
            this.$router.push({ name: 'extracto.index' })
        },
        openDialog (){
            this.dialog = true;
        },
        goBloqueo(v){
            this.$emit('set-bloqueo',  v);
        },
        goSepa(){
            if (this.tipo == "T")
                this.$router.push({ name: 'sepa.transfer' })
            else
                this.$router.push({ name: 'sepa.adeudo' })
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/remesa/'+this.id,{_method: 'delete'})
                .then(response => {
                    this.$router.push({ name: 'remesa.index' })
                    this.$toast.success('Remesa eliminada!');
            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
}
</script>
