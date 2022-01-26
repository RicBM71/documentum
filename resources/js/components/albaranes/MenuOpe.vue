<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
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
                    v-show="albaran.id > 0"
                    v-on="on"
                    color="white"
                    icon
                    :disabled="albaran.eje_fac > 0"
                    @click="openDialog"
                >
                    <v-icon color="primary">delete</v-icon>
                </v-btn>
            </template>
                <span>Borrar Registro</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="albaran.id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="goClonar">
                    <v-icon color="primary">repeat_one</v-icon>
                </v-btn>
            </template>
            <span>Clonar</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="computedMail"
                    v-on="on"
                    color="white"
                    icon
                    @click="goMail">
                    <v-icon color="primary">mail</v-icon>
                </v-btn>
            </template>
            <span>Enviar por email</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="!computedMail"
                    v-on="on"
                    color="white"
                    icon
                    @click="goQuitarNot">
                    <v-icon color="primary">unsubscribe</v-icon>
                </v-btn>
            </template>
            <span>Quitar notificación envío</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="albaran.id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="goCliente"
                >
                    <v-icon color="primary">group</v-icon>
                </v-btn>
            </template>
            <span>Ir a cliente</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="albaran.id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="printPDF"
                >
                    <v-icon color="primary">print</v-icon>
                </v-btn>
            </template>
            <span>Generar PDF</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="albaran.id > 0"
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
import Loading from '@/components/shared/Loading'
export default {
    props:{
        albaran: Object
    },
    components: {
        'my-dialog': MyDialog,
        'loading': Loading,
    },
    data () {
      return {
          dialog: false,
          show_loading: false
      }
    },
    computed:{
        computedMail(){
            if (this.albaran.notificado)
                return false;
            if (this.albaran.eje_fac == 0)
                return false;

            return true;
        }
    },
    methods:{
        goCreate(){
            this.$router.push({ name: 'albaran.create' })
        },
        goIndex(){
            this.$router.push({ name: 'albaran.index' })
        },
        goCliente(){
            this.$router.push({ name: 'cliente.edit', params: { id: this.albaran.cliente_id } })
        },
        goClonar(){
            this.show_loading = true;
            axios.put('/ventas/albacabs/'+this.albaran.id+'/clonar')
                .then(res => {
                    this.show_loading = false;
                    this.$router.push({ name: 'albaran.edit', params: { id: res.data.albaran.id } })

                })
                .catch(err => {
                    if (err.response.status == 404)
                        this.$toast.error("Albarán No encontrado!");
                    else
                        this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'albaran.index'})
                })

        },
        goMail(){
            this.show_loading = true;
            axios.put('/ventas/albacabs/'+this.albaran.id+'/mail')
                .then(res => {
                    this.show_loading = false;

                    this.$emit('refresh-alb',  res.data.albaran);

                    this.$toast.success('Mail enviado correctamente');
                })
                .catch(err => {
                    this.show_loading = false;
                    this.$toast.error(err.response.data);
                })
        },
        printPDF(){

            var url = '/ventas/albacabs/'+this.albaran.id+'/print';

            window.open(url, '_blank');
        },
        goQuitarNot() {

                this.show_loading = true;

                this.albaran.notificado = false;
                var url = "/ventas/albacabs/"+this.albaran.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.albaran)
                            .then(response => {
                                //console.log(response);
                                this.$toast.success(response.data.message);
                                this.albaran = response.data.albaran;
                                this.show_loading = false;
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
                                    console.log(err.response.data);
                                    this.$toast.error(err.response.data.message);
                                }
                                this.show_loading = false;
                            });
                        }
                    else{
                        this.show_loading = false;
                    }
                });

            },
        openDialog (){
            this.dialog = true;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/ventas/albacabs/'+this.albaran.id,{_method: 'delete'})
                .then(response => {
                this.albaranes = response.data;
                this.$router.push({ name: 'albaran.index' })
                this.$toast.success('Albarán eliminado!');

            })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
}
</script>
