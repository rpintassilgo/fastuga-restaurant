<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router"
import { ref, inject } from "vue"
import { useUserStore } from "c:/laragon/www/fastuga/fastuga-front/src/stores/user"

const router = useRouter()  
const toast = inject("toast")
const userStore = useUserStore()


const Pontos = () => {
  let confirmAction = confirm("Desenha gastar 10 pontos para receber 5 euros em desconto?");
        if (confirmAction && userStore.user?.points > 10) {
          alert("A realizar operação");
          
        } else {
          alert("Operação cancelada");
        }
        
}




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
            class="btn btn-primary btn1 px-4"
            @click="Pagar"

            >Pagar</button>
          
            <button
            id="botao"
            type="button"
            class="btn btn-primary btn1 px-4"
            @click="Pontos"
          
            
            >Usar Pontos</button>
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

.container {
  height: 100px;
  position: relative;

  
}

.btn1{
margin-right: 25px;
}

.btn2{
margin-left: 25px;
}

</style>