<script setup>


</script>

<template>
      <h1>Produtos que escolheu:</h1>
      <table>
    <tr>
        <th>Nome</th>
        <th>Valor</th>
    </tr>
    <tr>
        <td>
        <figcaption style="text-align:center;"><b>Alheira</b></figcaption>
        <div class="single-img"><img src="@/assets/alheira.jpg" alt="" width="200" height="125" style="text"/>    
        </div>
        </td>
        <td >10,00 €</td>
       
    </tr>
    <tr>
        <td>
          <figcaption style="text-align:center;"><b>Bitoque</b></figcaption>
          <div class="single-img"><img src="@/assets/bitoque.jpg" alt="" width="200" height="125"/> 
          </div>
        </td>
        <td >20,00 €</td>
    </tr>
</table>

<table>
    <tr>
        <td>Total a Pagar: </td>
        <td>30,00 €</td>
    </tr>

   
</table>


<div class="container">
      <div class="center">
            <button
            type="button"
            class="btn btn-primary px-5"

            >Pagar</button>
      </div>
</div>
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

</style>