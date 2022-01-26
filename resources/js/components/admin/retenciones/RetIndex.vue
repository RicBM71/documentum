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
                        :items="this.retenciones"
                        rows-per-page-text="Registros por página"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.nombre }}</td>
                                <td>{{ props.item.importe | currency('%', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
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
        titulo: "Retenciones",
        headers: [
          {
            text: 'Nombre',
            align: 'left',
            value: 'name'
          },
          {
            text: 'Valor',
            align: 'Right',
            value: 'importe'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        retenciones:[],
        status: false,
		registros: false,
        dialog: false,
        retencion_id: 0
      }
    },
    mounted()
    {

        axios.get('/admin/retenciones')
            .then(res => {

                this.retenciones = res.data;
                this.registros = true;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    methods:{
        create(){
            this.$router.push({ name: 'ret.create'})
        },
        extrae: function(role){ // extrae los permisos de cada role.

            var i=0;
            var roles = "";
            role.forEach(function(element) {
            roles += (element.name);
            ++i;
            if (i < role.length)
                roles+=", ";
            });

            return roles;
        },
        editItem (id) {
            this.$router.push({ name: 'ret.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.retencion_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/retenciones/'+this.retencion_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Retención eliminada!');
                    this.retenciones = response.data;
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
