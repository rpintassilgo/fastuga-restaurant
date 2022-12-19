<script>
import axios from 'axios'
export default {
 name: 'SignUp',
 data() {
   return {
     name: '',
     email:'',
     password:'',
     type:''

   }
 },
 methods: {
   async signUp() {
     let result = await axios.post("http://fastuga.test/api/users",{
      name:this.name,
      email:this.email,
      password:this.password,
      type:this.type
     });

     console.warn(result);

     if (result.status == 201) {
       localStorage.setItem("user-info",JSON.stringify(result.data))
       this.$router.push({name:'Home'})
     }
   }
 },

 mounted()
 {
   let user = localStorage.getItem('user-info');

   if (user) {
     this.$router.push({name:'Home'})
   }
 }
}
</script>

 <template>
  <div class="register">
    <input type="text" v-model="name" placeholder="Enter Name" />
    <input type="text" v-model="email" placeholder="Enter Email" />
    <input type="password" v-model="password" placeholder="Enter Password" />
    <input type="text" v-model="type" placeholder="Enter type" />
    <button v-on:click="signUp">SignUp</button>
    <p>
        <router-link to="/login">Login</router-link>
    </p>
  </div>
</template>

