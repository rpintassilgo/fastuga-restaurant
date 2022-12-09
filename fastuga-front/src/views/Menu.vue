<script setup>
import { ref, computed, onMounted, inject } from 'vue'

const filterByCompleted = ref(-1)

/*
const filteredTasks = computed( () => {
    return tasks.value.filter(t =>
        (props.onlyCurrentTasks && !t.completed)
        ||
        (!props.onlyCurrentTasks && (
          (filterByProjectId.value == -1
            || filterByProjectId.value == t.project_id
          ) &&
          (filterByCompleted.value == -1
            || filterByCompleted.value == 0 && !t.completed
            || filterByCompleted.value == 1 && t.completed
          ))))

  })
*/

</script>

<template>
   <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectCompleted"
        class="form-label"
      >Filtrar Menu Por:</label>
      <select
        class="form-select"
        id="selectCompleted"
        v-model="filterByCompleted"
      >
        <option value="-1">Nenhum Filtro</option>
        <option value="0">Pratos Quentes</option>
        <option value="1">Pratos Frios</option>
        <option value="2">Sumos</option>
        <option value="3">Sobremesas</option>
      </select>
    </div>

      <h1>Produtos: </h1>

    <div class="wrapper">
      <p style="text-align:center; align-items: center; margin-top: 1%;">
        <b>PRATOS QUENTES</b>
      </p>

      <div class="img-area">
        <router-link :to="{ name: 'ProdutosMenu' }" @click="clickProdutosMenu">
        <div class="single-img"><a href="/ProdutosMenu"><img src="@/assets/alheira.jpg" alt="" width="233" height="200" style="text"/></a>
          <figcaption style="text-align:center;"><b>Alheira</b></figcaption>
        </div>
      </router-link>
      <router-link :to="{ name: 'ProdutosMenu' }" @click="clickProdutosMenu">
        <div class="single-img"><img src="@/assets/arroz-marisco.jpg" alt="" width="233" height="200"/>
          <figcaption style="text-align:center;"><b>Arroz de Marisco</b></figcaption>
        </div>
      </router-link>
        <router-link :to="{ name: 'ProdutosMenu' }" @click="clickProdutosMenu">
        <div class="single-img"><img src="@/assets/bacalhau-a-bras.jpg" alt="" width="233" height="200"/>
          <figcaption style="text-align:center;"><b>Bacalhau à Braz</b></figcaption>
        </div>
      </router-link>
        <router-link :to="{ name: 'ProdutosMenu' }" @click="clickProdutosMenu">
        <div class="single-img"><img src="@/assets/bitoque.jpg" alt="" width="233" height="200"/>
          <figcaption style="text-align:center;"><b>Bitoque</b></figcaption>
        </div>
      </router-link>
        <router-link :to="{ name: 'ProdutosMenu' }" @click="clickProdutosMenu">
        <div class="single-img"><img src="@/assets/caldo-verde.jpg" alt="" width="233" height="200"/>
          <figcaption style="text-align:center;"><b>Caldo Verde</b></figcaption>
        </div>
      </router-link>
      </div>
    </div>

    <task-table
    :tasks="filteredTasks"
  ></task-table>
</template>

<style>

h1 {
      text-align: center;
}

td, th {
  padding: .7em;
  margin: 0;
  border: 1px solid #ccc;
  text-align: center;
}

th{
  background-color: #EEE;
}
td{
  font-weight:bold;
  background-color: #EEE;
}

table{
  width: 100%;
  margin-bottom : .5em;
  table-layout: fixed;
  text-align: center;
}

.center {
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
.container {
  height: 100px;
  position: relative;

}
b {
  color: black;
  text-decoration:none;
}

</style>