<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="user.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <v-text-field
                                v-model="user.name"
                                v-validate="'required'"
                                :error-messages="errors.collect('name')"
                                label="Nombre"
                                data-vv-name="name"
                                data-vv-as="nombre"
                                required
                            >
                            </v-text-field>
                            <v-text-field
                                v-model="user.username"
                                v-validate="'required|min:6'"
                                :counter="20"
                                :error-messages="errors.collect('username')"
                                label="Usuario"
                                data-vv-name="username"
                                data-vv-as="usuario"
                                required
                            >
                            </v-text-field>
                            <v-text-field
                                v-model="user.email"
                                v-validate="'required|email'"
                                :error-messages="errors.collect('email')"
                                label="email"
                                data-vv-name="email"
                                required
                            >
                            </v-text-field>
                            <v-text-field
                                v-model="password"
                                ref="password"
                                :append-icon="show ? 'visibility_off' : 'visibility'"
                                :type="show ? 'text' : 'password'"
                                :error-messages="errors.collect('password')"
                                v-validate="'min:8'"
                                label="password"
                                hint="Indicar password solo si va a modificarla"
                                data-vv-name="password"
                                @click:append="show = !show"
                                >
                            </v-text-field>
                            <v-text-field
                                v-model="password_confirmation"
                                v-validate="'min:8|confirmed:password'"
                                :append-icon="show ? 'visibility_off' : 'visibility'"
                                :type="show ? 'text' : 'password'"
                                :error-messages="errors.collect('password_confirmation')"
                                label="confirmar password"
                                hint="Confirmar password solo si va a modificarla"
                                data-vv-name="password_confirmation"
                                data-vv-as="password"
                                @click:append="show = !show"
                                >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="user.lastname"
                                :error-messages="errors.collect('lastname')"
                                label="Apellidos"
                                data-vv-name="lastname"
                                data-vv-as="apellidos"
                            >
                            </v-text-field>
                            <v-switch
                                v-model="user.blocked"
                                color="primary"
                                label="Bloqueado"
                            ></v-switch>
                            <v-menu v-if="user.blocked"
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
                                    :value="computedDateFormat"
                                    clearable
                                    label="Fecha Bloqueo"
                                    prepend-icon="event"
                                    readonly
                                    data-vv-as="F. bloqueo"
                                    @click:clear="clearDate"
                                    ></v-text-field>
                                <v-date-picker
                                    v-model="user.blocked_at"
                                    no-title
                                    locale="es"
                                    @input="menu2 = false"

                                ></v-date-picker>
                            </v-menu>
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                            >
                            </v-text-field>
                            <v-text-field
                                v-model="computedFCreFormat"
                                label="Creado"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm4>
                        </v-flex>
                        <v-flex sm6>
                            <div class="text-xs-center">
                                <v-btn @click="submit"  round  :loading="enviando" block  color="primary">
                                    Guardar Usuario
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
    import {mapGetters} from 'vuex';
    import MenuOpe from './MenuOpe'

	export default {
		$_veeValidate: {
      		validator: 'new'
    	},
        components: {
            'menu-ope': MenuOpe

		},
    	data () {
      		return {
                user: {
                    id:       0,
                    name:     "",
                    lastname: "",
                    username: "",
                    email:    "",
                    blocked_at:"",
                    blocked:  "",
                    updated_at:"",
                    created_at:"",
                },
                password: "",
                password_confirmation:"",
                titulo:   "Usuarios",
                role_user: [],
                permisos:[],
                permisos_selected:[],

        		status: false,
        		msg : "",
                color: "error",
                icon: "warning",
                enviando: false,

                show: false,
                menu2: false,

                showPer: false,
      		}
        },
        mounted(){
            // if (!this.isRoot)
            //     this.items.splice(3,1)

            axios.get('/admin/users/create')
                .then(res => {
                    this.showMainDiv = true;
                })
                .catch(err => {
                     if (err.response.status != 401){
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'users'});
                        }
                })
        },
        computed: {
            ...mapGetters([
	    		'isRoot'
    		]),
            computedDateFormat() {
                moment.locale('es');
                return this.user.blocked_at ? moment(this.user.blocked_at).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.user.updated_at ? moment(this.user.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.user.created_at ? moment(this.user.created_at).format('D/MM/YYYY H:mm:ss') : '';
            },

        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/admin/users";

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.post(url, this.user)
                            .then(response => {

                                this.$router.push({ name: 'users.edit', params: { id: response.data.user.id } })
                                this.enviando = false;
                            })
                            .catch(err => {
                                    const msg_valid = err.response.data.errors;
                                    for (const prop in msg_valid) {
                                        this.errors.add({
                                            field: prop,
                                            msg: `${msg_valid[prop]}`
                                        })
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
                this.user.blocked_at = null;
            }

    }
  }
</script>
