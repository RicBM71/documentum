<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goExport"
                >
                    <v-avatar size="32px">
                        <img class="img-fluid" src="/assets/export.jpg">
                    </v-avatar>
                </v-btn>
            </template>
            <span>Exportar a Excel</span>
        </v-tooltip>
    </div>
</template>
<script>
export default {
    props:{
        fecha: String
    },
    data () {
      return {
          dialog: false,
      }
    },
    methods:{
        goExport(){
            //console.log(this.fecha);
            var e = this.fecha.split('-')
            var url = '/mto/extractos/'+e[0]+'/export/';

            window.open(url);
        },
    }
}
</script>
