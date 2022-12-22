<script setup>
import Chart from "chart.js/auto";
import { ref, watch, computed, onMounted, inject } from "vue";

const axios = inject("axios");

const statistics = [];
const productbyType = [];
const typeProducts = ["hot dish", "cold dish", "drink", "dessert"];

const props = defineProps({
  msg: String,
});

const vartypes = async (type) => {
  try {
    const resp = await axios.get(`statistics/${type}`, {
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + sessionStorage.getItem("token"),
      },
    });
    statistics.value = resp.data;

    // console.log(statistics.value);

    return JSON.parse(JSON.stringify(statistics.value));
  } catch (error) {
    console.log(error);
  }
};

const graph = async () => {
  for (let index = 0; index < typeProducts.length; index++) {
    // console.log(typeProducts);
    const countProducts = await vartypes(typeProducts[index]);
    // console.log(countProducts);

    // console.log(index);

    // console.log(countProducts[2]);

    for (let x = 0; x < countProducts.length; x++) {
      productbyType.push(countProducts[x]);
      // countProducts.shift();
      // console.log(productbyType);
    }
  }
};

onMounted(async () => {
  // console.log("component mounted");
  // console.log(productbyType);

  const ctx = document.getElementById("myChart"); // node

  await graph();

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
});
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
  width: 40%;
  margin-top: 1%;
  border: 3px solid black;
  padding: 10px;
}
</style>

   