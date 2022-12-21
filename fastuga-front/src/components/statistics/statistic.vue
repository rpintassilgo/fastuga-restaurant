<script>
import Chart from 'chart.js/auto'
import {ref} from 'vue'
import axios from 'axios';
const statistics = ref([])
const serverBaseUrl = import.meta.env.VITE_APP_BASE_URL

async function var2022(year) {

      try {
        //axios.defaults.headers.common['Authorization'] = "Bearer " + sessionStorage.getItem('token');
        const resp = await axios.get(serverBaseUrl + '/api/statistics/' + year , 
        {
          headers: {
            'Content-Type': 'application/json',
            'Authorization' : "Bearer " + sessionStorage.getItem('token'),
            "Access-Control-Allow-Origin": "*",
            'Access-Control-Allow-Credentials':true,
            'Access-Control-Allow-Methods':'GET,PUT,POST,DELETE,PATCH,OPTIONS'

          }
        })
        statistics.value = resp.data
        return statistics.value
      
      } catch (error) {
        console.log(error)
      }
}
 await(var2022(2022));

export default {
  name: 'Hello',
  props: {
    msg: String
  },
  mounted(){
    console.log('component mounted')

    const ctx = document.getElementById('myChart'); // node
    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['2017', '2018', '2019', '2020', '2021', '2022'],
        datasets: [{
          label: '# of Users',
          data: [0,2,3,5,10,5,var2022(2022)],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    myChart;
  }
}
  </script>

<template> 
<div class="hello">
  <h1> {{ msg }}</h1> 
 <canvas id="myChart" width="200" height="200 "></canvas>
</div>
</template>



<style>
.hello {
  margin: auto;
  width: 45%;
  margin-top: 5%;
  border: 3px solid black;
  padding: 10px;
}
</style>
   