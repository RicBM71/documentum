<template>
    <v-app>
          <div class="text-xs-center">
            <v-dialog
            v-model="myEmpresa"
            width="500"
            >
            <v-card>
                <v-card-title
                class="headline grey lighten-2"
                primary-title
                >
                Seleccionar empresa
                </v-card-title>

                <v-card-text>
                    <v-flex sm2 d-flex></v-flex>
                    <v-flex sm8 d-flex>
                        <v-select
                            v-on:change="setEmpresa"
                            v-model="empresa_id"
                            item-text="name"
                            item-value="id"
                            :items="empresas"
                            label="Empresa"
                        ></v-select>
                    </v-flex>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    flat
                    @click="myEmpresa=false"
                >
                    Cerrar
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </div>
        <div v-if="isLoggedIn">
            <v-navigation-drawer
                v-if="isLoggedIn"
                v-model="drawer"
                :clipped="$vuetify.breakpoint.lgAndUp"
                fixed
                app
                >
                <v-list dense>
                    <template v-for="item in items">
                    <v-layout
                        v-if="item.heading"
                        :key="item.heading"
                        row
                        align-center
                    >
                        <v-flex xs6>
                        <v-subheader v-if="item.heading">
                            {{ item.heading }}head
                        </v-subheader>
                        </v-flex>
                        <v-flex xs6 class="text-xs-center">
                        <a href="#!" class="body-2 black--text">EDIT</a>
                        </v-flex>
                    </v-layout>
                    <v-list-group
                        v-else-if="item.children"
                        :key="item.text"
                        v-model="item.model"
                        :prepend-icon="item.model ? item.icon : item['icon-alt']"
                        append-icon=""
                    >
                        <v-list-tile slot="activator">
                        <v-list-tile-content>
                            <v-list-tile-title>
                            {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile
                        v-for="child in item.children"
                        :key="child.name"
                        :to="{ name: child.name}"
                        >
                        <v-list-tile-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                            {{ child.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                    <v-list-tile v-else :key="item.text" @click="abrir(item.name)">
                        <v-list-tile-action>
                        <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                        <v-list-tile-title>
                            {{ item.text }}
                        </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    </template>
                </v-list>
            </v-navigation-drawer>
            <v-toolbar
                v-if="menu"
                :clipped-left="$vuetify.breakpoint.lgAndUp"
                color="blue darken-3"
                dark
                app
                fixed
                >
                <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                    <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                    <span class="hidden-sm-and-down">{{ empresaTxt }}</span>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon v-on:click="alertjobs" v-show="jobs > 0">
                   <span class="caption yellow--text darken-4">{{jobs}}</span> <v-icon color="yellow">notification_important</v-icon>
                </v-btn>
                <v-btn icon v-on:click="empresa">
                    <v-icon>work_outline</v-icon>
                </v-btn>
                <v-btn icon v-on:click="home">
                    <v-icon>home</v-icon>
                </v-btn>
                <v-btn icon v-on:click="passwd">
                    <v-avatar v-if="user.avatar !='#'" size="32px">
                        <img class="img-fluid" :src="user.avatar">
                    </v-avatar>
                    <v-icon v-else>settings</v-icon>
                </v-btn>
                <strong v-html="user.name"></strong>
                <v-btn icon large  v-on:click="Logout">
                    <v-avatar size="32px" tile>
                        <v-icon>exit_to_app</v-icon>
                    </v-avatar>
                </v-btn>
                </v-toolbar>
            <v-content>
                <v-container fluid>

                    <router-view :key="$route.fullPath"></router-view>

                </v-container>
            </v-content>
        </div>
</v-app>
</template>
<script>
import {mapActions} from "vuex";
import {mapState} from 'vuex'
import {mapGetters} from 'vuex';
export default {
    computed:{
        ...mapState({
            user: state => state.auth
        }),
        ...mapGetters([
            'isLoggedIn',
            'isRoot',
            'isAdmin',
            'hasFactura'
		]),
    },
    data: () => ({
        menu: true,
        dialog: false,
        drawer: false,
        show: true,

        empresaTxt:"Sanaval",
        empresas:[],
        myEmpresa:false,
        empresa_id:0,
        jobs: 0,

        // user: {
        //     name : ""
        // },

        root: {
            icon: 'keyboard_arrow_up',
            'icon-alt': 'keyboard_arrow_down',
            text: 'Administrador',
            model: false,
            children: [
                { text: 'Usuarios', name: 'users.index' },
                { text: 'Roles', name: 'roles' },
                { text: 'Empresas', name: 'empresa.index' },
                { text: 'Retenciones (IRPF)', name: 'ret.index' },
                { text: 'Cuentas', name: 'cuenta.index' },
                { text: 'Tipos de IVA', name: 'iva.index' },
                { text: 'Archivos', name: 'archivo.index' },
                { text: 'Formas de Pago', name: 'fpago.index' },
                { text: 'Contadores', name: 'contador.index' },
            ]
        },

        admin: {
            icon: 'keyboard_arrow_up',
            'icon-alt': 'keyboard_arrow_down',
            text: 'Administrador',
            model: false,
            children: [
                { text: 'Usuarios', name: 'users' },
                { text: 'Empresas', name: 'empresa.index' },
                { text: 'Retenciones (IRPF)', name: 'ret.index' },
                { text: 'Cuentas', name: 'cuenta.index' },
                { text: 'Tipos de IVA', name: 'iva.index' },
                { text: 'Archivos', name: 'archivo.index' },
                { text: 'Formas de Pago', name: 'fpago.index' },
                { text: 'Contadores', name: 'contador.index' },
            ]
        },

        factu:{
            icon: 'keyboard_arrow_up',
            'icon-alt': 'keyboard_arrow_down',
            text: 'Facturación y Remesas',
            model: false,
            children: [
                { text: 'Albaranes y Facturas', name: 'albaran.index' , icon: 'create_new_folder'},
                { text: 'Remesa Domiciliaciones', name: 'adeudo.index', icon: 'cloud_download' },
                { text: 'Remesas', name: 'remesa.index', icon: 'airplay' },
                { text: 'Emitir Transferencias', name: 'sepa.transfer', icon: 'present_to_all'},
                { text: 'Imprimir facturas', name: 'facturas.print', icon: 'print'},
                { text: 'Vencimientos', name: 'vencimiento.index' },
            ]
        },

        items: [
            { icon: 'people', text: 'Clientes-Proveedores', name:'cliente.index' },
            { icon: 'local_offer', text: 'Productos', name:'producto.index' },
            { icon: 'account_balance', text: 'Extracto Banco', name:'extracto.index' },
            { icon: 'open_in_browser', text: 'Importar N.43', name:'extracto.importar' },
            { icon: 'save_alt', text: 'Documentos', name:'documento.index' },
            { icon: 'save_alt', text: 'Enlazar Extracto-Documento', name:'extracto.link' },
            { icon: 'get_app', text: 'Download ZIP', name:'documento.zip' },
            { icon: 'folder', text: 'Carpetas', name:'carpeta.index' },
        ]
    }),
    mounted(){

        axios.get('/dash')
                .then(res => {

                    this.jobs = res.data.jobs;

                    this.setAuthUser(res.data.user);

                    this.empresa_id = this.user.empresa_id;
                    //this.show = true;
                    if (this.hasFactura)
                        this.items.push(this.factu);

                    if (this.isRoot)
                        this.items.push(this.root);
                    else if(this.isAdmin)
                        this.items.push(this.admin);

                    res.data.user.empresas.map((e) =>{
                        if (e.id == this.empresa_id)
                            this.empresaTxt = e.titulo;
                        this.empresas.push({id: e.id, name: e.titulo});
                    })



                })
                .catch(err => {
                    //console.log(err);
                    this.show = false;
                    if (err.request.status == 401){ // fallo de validated.
                        //this.$router.push("/login");
                        window.location = '/login';
                    }
                })

    },
    methods:{
        ...mapActions([
				'setAuthUser'
			]),
        abrir(name){
            this.$router.push({name: name});
        },
        home(){
            axios.get('/dash')
                .then(res => {
                    this.jobs = res.data.jobs;
                    this.setAuthUser(res.data.user);

                    this.empresa_id = this.user.empresa_id;

                    res.data.user.empresas.map((e) =>{
                        if (e.id == this.empresa_id)
                            this.empresaTxt = e.titulo;
                        this.empresas.push({id: e.id, name: e.titulo});
                    })
                })
                .catch(err => {
                    this.show = false;
                    if (err.request.status == 401){ // fallo de validated.
                        window.location = '/login';
                    }
                })

            this.$router.push({name: 'dash'});
        },
        alertjobs(){
            alert("Hay "+this.jobs+" jobs pendientes de envío!");
        },
        passwd(){
            this.$router.push({name: 'edit.password'});
        },
        empresa(){
            this.myEmpresa=true;
        },
        getReloadEmpresa(){
            axios.get('/dash')
                .then(res => {

                    this.setAuthUser(res.data.user);

                })
                .catch(err => {
                    this.$toast.error("Fallo en reload empresa...");
                })
        },
        setEmpresa(){

            this.empresas.map((e) =>{
                   if (e.id == this.empresa_id)
                        this.empresaTxt = e.name;
                });

            axios({
                    method: 'put',
                    url: '/admin/users/'+this.user.id+'/empresa',
                    data:{ empresa_id: this.empresa_id }
                })
                .then(res => {
                    //this.$toast.success("Cambiando de empresa...");
                   // this.setAuthUser(res.data.user);

                    this.getReloadEmpresa();
                    this.$router.push({name: 'dash'});
                })
                .catch(err => {
                    this.$toast.error("No se ha podido seleccionar la empresa");
                });

            this.myEmpresa=false;
        },
        Logout() {
            this.$toast.success('Logout, espere...');
            axios.post('/logout').then(res => {
                console.log(res);
                this.$store.dispatch('unsetAuthUser')
                this.$router.push({name: 'index'});
			})
        },
    }
  }
</script>
