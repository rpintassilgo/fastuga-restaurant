<script setup>
import Chart from "chart.js/auto";
import { ref, watch, computed, onMounted, inject } from "vue";

const axios = inject("axios");

const statistics = ref([])
const userbyYear = [];
const years = [2017,2018,2019,2020,2021,2022]

const props = defineProps({
  msg: String,
});

async function var2022(year) {

      try {
        const resp = await axios.get(`statistics/${year}`, 
        {
          headers: {
            "Content-Type": "application/json",
          Authorization: "Bearer " + sessionStorage.getItem("token"),
      },
        })
        statistics.value = resp.data
        //console.log("return " + JSON.parse(statistics.value))
        return JSON.parse(statistics.value)
      
      } catch (error) {
        console.log(error)
      }
}


const graph = async () => {
  for (let index = 0; index < years.length; index++) {
  const CountUsers = (await(var2022(years[index])))
  userbyYear.push(CountUsers);
}
};

onMounted(async () => {
  // console.log("component mounted");
  // console.log(productbyType);

  const ctx = document.getElementById('myChart'); // node

  await graph();

  const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['2017', '2018', '2019', '2020', '2021', '2022'],
        datasets: [{
          label: '# of Users',
          data: [userbyYear[0],userbyYear[1],userbyYear[2],userbyYear[3],userbyYear[4],userbyYear[5]],
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
});
</script>

<template> 
<p></p>
<a href="/statistic_products">Search for Products by type</a>
<h1 style="margin-top: 20px;"> Accounts Created By Year</h1>
            

<div class="hello">
  <h1> {{ msg }}</h1> 
 <canvas id="myChart" width="200" height="200 "></canvas>
</div>
</template>



<style>
.left{
margin-left: 20%;
}
.right{
margin-left: 40%;
}
.hello {
  margin: auto;
  width: 45%;
  margin-top: 1%;
  border: 3px solid black;
  padding: 10px;
}
</style>
   