<template>
  <v-layout v-if="albalin_id > 0" row justify-center>
    <v-dialog v-model="dialog_edt" persistent max-width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">Editar línea</span>
        </v-card-title>
        <v-card-text>
            <v-form>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex sm4>
                            <v-autocomplete
                                v-model="albalin.producto_id"
                                v-validate="'required'"
                                data-vv-name="producto_id"
                                data-vv-as="Producto"
                                @change="selPro(albalin.producto_id)"
                                :error-messages="errors.collect('producto_id')"
                                :items="productos"
                                flat
                                label="Producto"
                                required
                            >
                            </v-autocomplete>
                        </v-flex>
                        <v-flex sm4>
                                <v-text-field
                                    v-model="albalin.nombre"
                                    :error-messages="errors.collect('nombre')"
                                    label="Nombre"
                                    data-vv-name="nombre"
                                    data-vv-as="nombre"
                                    required
                                    v-on:keyup.enter="submit"
                                >
                                </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albalin.porirpf"
                                v-validate="'required|decimal:2'"
                                :error-messages="errors.collect('porirpf')"
                                label="IRPF "
                                data-vv-name="porirpf"
                                data-vv-as="IRPF"
                                required
                                class="inputPrice"
                                type="number"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albalin.poriva"
                                v-validate="'required|decimal:2'"
                                :error-messages="errors.collect('poriva')"
                                label="IVA"
                                data-vv-name="poriva"
                                data-vv-as="IVA"
                                required
                                class="inputPrice"
                                type="number"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout wrap>
                        <v-flex sm4></v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albalin.unidades"
                                v-validate="'required|decimal:0'"
                                :error-messages="errors.collect('unidades')"
                                label="Unidades"
                                ref="unidades"
                                data-vv-name="unidades"
                                data-vv-as="unidades"
                                required
                                class="inputPrice"
                                type="number"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albalin.impuni"
                                v-validate="'required|decimal:2'"
                                :error-messages="errors.collect('impuni')"
                                label="Importe Ud."
                                data-vv-name="impuni"
                                data-vv-as="Importe Ud."
                                required
                                class="inputPrice"
                                type="number"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albalin.dto"
                                v-validate="'required|decimal:0'"
                                :error-messages="errors.collect('dto')"
                                label="Dto"
                                ref="dto"
                                data-vv-name="dto"
                                data-vv-as="dto"
                                required
                                class="inputPrice"
                                type="number"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="computedImporte"
                                label="Importe"
                                required
                                class="inputPrice"
                                type="number"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>

                    </v-layout>
                </v-container>
            </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="closeDialog">Cerrar</v-btn>
          <v-btn color="blue darken-1" flat @click="submit" :loading="loading">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>
<script>
  export default {
    props:{
        albalin_id: Number,
        dialog_edt: Boolean,
        albalin: Object
    },
    data: () => ({
        loading: false,
        productos:[],
        iva:[],
        irpf:[],
        // albalin: {
        //     id:"",
        //     albacab_id:"",
        //     producto_id: "",
        //     nombre:"",
        //     unidades:"",
        //     impuni:0,
        //     poriva:0,
        //     porirpf:0,
        //     dto:0,
        //     importe:0,
        //     username: "",
        //     updated_at:"",
        //     created_at:"",
        // },
    }),
    mounted(){
        axios.get('/ventas/albalins/create')
            .then(res => {
                this.productos = res.data.productos;
                this.iva = res.data.iva;
                this.irpf = res.data.irpf;
            })
            .catch(err => {
                this.$toast.error(err.response.data.message);
            })
    },
    computed:{
        computedImporte(){
            if (this.albalin.unidades != ""){
                if (this.albalin.dto == 0)
                    this.albalin.importe =  parseFloat(this.albalin.unidades) * parseFloat(this.albalin.impuni);
                else{
                    var dto = (parseFloat(this.albalin.impuni) * parseFloat(this.albalin.dto) / 100).toFixed(2);
                    this.albalin.importe =  parseFloat(this.albalin.unidades) * (parseFloat(this.albalin.impuni) - dto);
                }

                return this.albalin.importe;
            }
        }
    },
    methods:{
        closeDialog (){
            this.$emit('update:dialog_edt', false)
        },
        selPro(producto_id){
            axios.get('/mto/productos/'+producto_id)
            .then(response => {

                this.albalin.poriva = response.data.producto.iva.importe;
                this.albalin.porirpf = response.data.producto.retencion.importe;
                this.albalin.impuni = response.data.producto.importe;

                this.$refs.unidades.focus();
            })
            .catch(err => {
                console.log(err);
            });
        },
        submit() {
            this.loading = true;

            var url = "/ventas/albalins/"+this.albalin_id;

            this.$validator.validateAll().then((result) => {
                if (result){

                    axios.put(url, this.albalin)
                        .then(res => {

                            this.$emit('update:dialog_edt', false)
                            this.loading = false;

                            this.$emit('reLoadLineas', res.data.lineas, res.data.totales)
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
                                this.$toast.error(err.response.data.message);
                            }
                            this.loading = false;
                        });
                    }
                else{
                    this.loading = false;
                }
            });

        }
      }
  }
</script>

<style scoped>


.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
