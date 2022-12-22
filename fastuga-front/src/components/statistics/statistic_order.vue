<script>
import Chart from "chart.js/auto";
import { ref } from "vue";
import axios from "axios";

const statistics = ref([]);
const productbyType = [];
const serverBaseUrl = import.meta.env.VITE_APP_BASE_URL;

async function vartypes(type) {
  try {
    const resp = await axios.get(serverBaseUrl + "/api/statistics/type/" + type, {
      headers: {
        "Content-Type": " ",
        Authorization: "Bearer " + sessionStorage.getItem("token"),
      },
    });
    statistics.value = resp.data;
    console.log(JSON.stringify(statistics.value));

    return JSON.parse(JSON.stringify(statistics.value));
  } catch (error) {
    console.log(error);
  }
}

const typeProducts = ["hot dish", "cold dish", "drink", "dessert"];
for (let index = 0; index < typeProducts.length; index++) {
  const countProducts = await vartypes(typeProducts[index]);
  for (let x = 0; x < countProducts.length; x++) {
    productbyType.push(countProducts[0]);
    countProducts.shift();
  }

}

export default {
  name: "Hello",
  props: {
    msg: String,
  },
  mounted() {
    console.log("component mounted");

    const ctx = document.getElementById("myChart"); // node

    const myChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Hot Dishes", "Cold Dishes", "Drinks", "Desserts"],
        datasets: [
          {
            label: "# of Produt",
            data: [
              productbyType[0].count,
              productbyType[1].count,
              productbyType[2].count,
              productbyType[3].count,
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });

    myChart;
  },
};
</script>

<template>
  <div>
    <p></p>
    <a href="statistic">Back</a>
    <h1>Search for Products by type</h1>

    <div class="hello">
      <h1>{{ msg }}</h1>
      <canvas id="myChart" width="200" height="200 "></canvas>
    </div>
  </div>
</template>


<style>
.left {
  margin-left: 20%;
}
.right {
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

   