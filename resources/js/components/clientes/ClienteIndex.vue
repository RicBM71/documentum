<template>
    <div>
        <loading :show_loading="show_loading"></loading>
            <div v-if="registros">
                <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
                <v-card>
                    <v-card-title>
                        <h2>{{titulo}}</h2>
                        <v-spacer></v-spacer>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    v-on="on"
                                    color="white"
                                    icon
                                    @click="filtro = !filtro"
                                >
                                    <v-icon color="primary">filter_list</v-icon>
                                </v-btn>
                            </template>
                            <span>Filtros</span>
                        </v-tooltip>
                        <menu-ope></menu-ope>
                    </v-card-title>
                </v-card>
                 <v-card v-show="filtro">
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-spacer></v-spacer>
                            <v-flex xs2 d-flex>
                                <v-select
                                    v-model="fbaja"
                                    :items="sino"
                                    label="Clientes"
                                ></v-select>
                            </v-flex>
                            <v-flex sm1>
                                <v-btn @click="filtrar"  round  block  color="info">
                                    Filtrar
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-card>
                <v-card>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs6></v-flex>
                            <v-flex xs6>
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="search"
                                    label="Buscar"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                        <br/>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-data-table
                                :headers="headers"
                                :items="clientes"
                                @update:pagination="updateEventPagina"
                                :pagination.sync="pagination"
                                :search="search"
                                rows-per-page-text="Registros por página"
                                >
                                    <template slot="items" slot-scope="props">
                                        <td :class="baja(props.item.fechabaja)">{{ props.item.nombre }}</td>
                                        <td>{{ props.item.cif }}</td>
                                        <td>{{ props.item.email }}</td>
                                        <td>{{ props.item.telefono1 }}</td>
                                        <td>{{ props.item.patron }}</td>
                                        <td class="justify-center layout px-0">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                @click="editItem(props.item.id)"
                                            >
                                                edit
                                            </v-icon>


                                            <v-icon
                                            v-show="isAdmin"
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
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo:"Clientes/Proveedores",
        search:"",
        paginaActual:{},
        pagination:{
            model: "clientes",
            descending: false,
            page: 1,
            rowsPerPage: 10,
            sortBy: "nombre",
            search: ""
        },
        headers: [
            {
            text: 'Nombre',
            align: 'left',
            value: 'nombre'
            },
            {
            text: 'CIF',
            align: 'left',
            value: 'cif'
            },
            {
            text: 'email',
            align: 'left',
            value: 'email'
            },
            {
            text: 'Teléfono',
            align: 'left',
            value: 'telefono1'
            },
            {
            text: 'Patrón N.43',
            align: 'left',
            value: 'patron'
            },
            {
            text: 'Acciones',
            align: 'Center',
            value: ''
            }
        ],
        clientes:[],
        status: false,
		registros: false,
        dialog: false,
        cliente_id: 0,
        show_loading: true,
        filtro: false,
        fbaja: 'A',
        sino: [
            {text:'Alta','value':'A'},
            {text:'Baja','value':'B'},
            {text:'Todos','value':'T'}
        ]
      }
    },
    mounted()
    {
        if (this.getPagination.model == this.pagination.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        axios.get('/mto/clientes')
            .then(res => {

                this.clientes = res.data;
                this.registros = true;
                this.show_loading = false;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'getPagination'
        ]),
    },
    methods:{
        ...mapActions([
            'setPagination',
            'unsetPagination'
        ]),
        baja(fecha){
            //console.log(fecha);

            return (fecha == null) ? '' : 'red--text tachado';

        },
        updateEventPagina(obj){

            this.paginaActual = obj;

        },
        updatePosPagina(pag){

            this.pagination.page = pag.page;
            this.pagination.descending = pag.descending;
            this.pagination.rowsPerPage= pag.rowsPerPage;
            this.pagination.sortBy = pag.sortBy;

        },
        create(){
            this.$router.push({ name: 'cliente.create'})
        },
        editItem (id) {
            this.setPagination(this.paginaActual);
            if (this.isAdmin)
                this.$router.push({ name: 'cliente.edit', params: { id: id } })
            else
                this.$router.push({ name: 'cliente.show', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.cliente_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/clientes/'+this.cliente_id,{_method: 'delete'})
                .then(response => {

                this.clientes = response.data;
                this.$toast.success('Cliente eliminado!');


            })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
        filtrar(){

            this.$validator.validateAll().then((result) => {
                    if (result){
                        this.show_loading = true;
                        axios.post('/mto/clientes/filtrar',
                                {
                                    fbaja: this.fbaja,
                                }
                            )
                            .then(res => {
                                //console.log(res);
                                this.pagination.page = 1;
                                this.filtro = false;

                                this.clientes   = res.data;
                                this.show_loading = false;

                            })
                            .catch(err => {

                                this.show_loading = false;
                                if (err.response.status != 419)
                                    this.$toast.error(err.response.data.message);
                                else
                                    this.$toast.error("Sesión expirada!");

                            });
                    }
            });

        },

    }
  }
</script>
<style scoped>
.tachado{
    text-decoration: line-through
}
</style>
