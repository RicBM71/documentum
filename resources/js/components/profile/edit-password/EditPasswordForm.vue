<template>
	<div>
		<v-form @submit.prevent="updatePassword">
            <v-layout row wrap>
                <v-flex sm3></v-flex>
                <v-flex sm6>
                    <v-text-field
                        v-model="form.new_password"
                        ref="new_password"
                        :append-icon="show ? 'visibility_off' : 'visibility'"
                        :counter="8"
                        :type="show ? 'text' : 'password'"
                        :error-messages="errors.collect('new_password')"
                        v-validate="'required|min:8'"
                        label="password"
                        hint="Indicar password "
                        data-vv-name="new_password"
                        data-vv-as="password"
                        :disabled="loading"
                        @click:append="show = !show"
                        >
                    </v-text-field>
                    <v-text-field
                        v-model="form.password_confirmation"
                        v-validate="'required|min:8|confirmed:new_password'"
                        :append-icon="show ? 'visibility_off' : 'visibility'"
                        :type="show ? 'text' : 'password'"
                        :error-messages="errors.collect('password_confirmation')"
                        label="confirmar password"
                        hint="Confirmar password"
                        data-vv-name="password_confirmation"
                        data-vv-as="password"
                        :disabled="loading"
                        @click:append="show = !show"
                        >
                    </v-text-field>

                    <v-btn @click="submit"  round  :loading="loading" block  color="primary">
                        <span v-show="loading">Actualizando Password</span>
                        <span v-show="!loading">Actualizar Password</span>
                    </v-btn>
                </v-flex>
            </v-layout>
		</v-form>
	</div>
</template>

<script>
    import {mapActions} from "vuex";
	export default {
        $_veeValidate: {
      		validator: 'new'
    	},
		data() {
			return {
                show: false,
				loading: false,
				form: {
					new_password: '',
					password_confirmation: ''
				}
			}
        },
        mounted(){
            // axios.get('/dash')
            //     .then(res => {
            //         this.setAuthUser(res.data.user);
            //     })
            //     .catch(err => {
            //         console.log(err)
            //     })
        },
		methods: {
            ...mapActions([
				'setAuthUser'
			]),
			submit() {

                this.$validator.validateAll().then((result) => {
                    if (result){
                        this.loading = true;
                        axios.post('/admin/users/password/update', this.form)
                            .then((res) => {
                               // console.log(res);
                                this.loading = false;
                                this.$toast.success(res.data);
                                this.$emit('updateSuccess');
                            })
                            .catch(err => {
                                //(err.response.data.error) &&  this.$toast.error(err.response.data.error);

                                const msg_valid = err.response.data.errors;
                                    //console.log(`obj.${prop} = ${msg_valid[prop]}`);
                                for (const prop in msg_valid) {
                                    this.$toast.error(`${msg_valid[prop]}`);
                                }
                                this.loading = false;
                            });
                    }
                })
			}
		}
	}
</script>
