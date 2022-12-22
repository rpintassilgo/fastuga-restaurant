<script>
import Chart from "chart.js/auto";
import { ref } from "vue";
import axios from "axios";

export default {
  name: "Hello",
  props: {
    msg: String,
  },
  mounted() {
    console.log("component mounted");
  },
};
</script>

<template>
  <div>
    <p></p>
    <p>
    </p>
    <p>
      <a href="/statistic_product">Search for Products by type</a>
    </p>
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
   