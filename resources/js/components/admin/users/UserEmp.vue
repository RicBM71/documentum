<template>
    <div v-show="show">
        <h3>Empresas</h3>

        <v-layout row wrap>
            <v-flex sm3
                v-for="item in empresas"
                :key="item.id"
            >
                <v-switch
                    v-on:change="setUserEmp"
                    v-model="emp_selected"

                    :label="item.nombre"
                    :value="item.id"
                    color="primary">
                ></v-switch>
            </v-flex>
        </v-layout>
    </div>
</template>
<script>
export default {
    props: ['user_id','emp_user'],
    data () {
        return {
            empresas: [],
            emp_selected: [],
            show: false
        }
    },
    mounted(){
        //console.log(this.emp_user);
            //cargamos todos las empresas disponibles
        axios.get('/admin/empresas')
            .then(res => {

                var emps = [];
                //console.log(res);
                res.data.map((e) => {
                    this.empresas.push({id: e.id, nombre: e.titulo});
                })

                this.show = (this.empresas.length > 0 );
            })
            .catch(err => {
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'users'})
            })

        this.emp_selected = this.emp_user;

    },
    methods:{
        setUserEmp(){

            axios({
                url: '/admin/users/'+this.user_id+'/empresas',
                method: 'put',
                data:
                    {
                        empresas: this.emp_selected,
                        seleccionadas: this.emp_selected.length
                    }
                })
                .then(res => {
                    this.$toast.success("Empresa asignada correctamente");
                    //this.setEmpresaDef();
                    })
                .catch(err => {

                    this.$toast.error("Error al asignar empresa");
                });
        },
        // setEmpresaDef(){
        //     console.log(this.emp_selected[0]);
        //     axios.put('/admin/users/'+this.user_id+'/empresa',{ empresa: this.emp_selected[0] })
        //     .then(res => {
        //             this.$toast.success("Empresa asignada correctamente");
        //             })
        //         .catch(err => {
        //             this.$toast.error("Error al establecer empresa x defecto");
        //         });
        // }
    }
}
</script>
