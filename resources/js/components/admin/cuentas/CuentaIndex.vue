<template>
    <div v-if="registros">
		<my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-data-table
                        :headers="headers"
                        :items="this.cuentas"
                        rows-per-page-text="Registros por página"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.nombre }}</td>
                                <td>{{ formatIBAN(props.item.iban) }}</td>
                                <td class="justify-center layout px-0">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="editItem(props.item.id)"
                                    >
                                        edit
                                    </v-icon>


                                    <v-icon
                                    small
                                    @click="openDialog(props.item.id)"
                                    >
                                    delete
                                    </v-icon>
                                </td>
                            </template>
                            <template slot="pageText" slot-scope="props">
                                Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import MenuOpe from './MenuOpe'
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
    },
    data () {
      return {
        headers: [
          {
            text: 'Nombre',
            align: 'left',
            value: 'name'
          },
          {
            text: 'IBAN',
            align: 'left',
            value: 'iban'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        cuentas:[],
        status: false,
		registros: false,
        dialog: false,
        cuenta_id: 0,
        titulo:"Cuentas"
      }
    },
    mounted()
    {

        axios.get('/admin/cuentas')
            .then(res => {
                this.cuentas = res.data;
                this.registros = true;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    methods:{
        formatIBAN(iban){
            if (iban == null) return null;
            return iban.slice(0,4)+" "+iban.slice(4,8)+"**********"+iban.slice(20,24);
        },
        create(){
            this.$router.push({ name: 'cuenta.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'cuenta.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.cuenta_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/cuentas/'+this.cuenta_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Cuenta eliminada!');
                    this.cuentas = response.data;
                }
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
