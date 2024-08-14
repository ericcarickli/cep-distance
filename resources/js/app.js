import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Import the router

const app = createApp(App);

app.use(router); // Use the router

app.mount('#app');


// import './bootstrap';
// import { createApp } from 'vue';
// import ExampleComponent from './components/ExampleComponent/ExampleComponent.vue';


// const app = createApp({});

// app.component('example-component', ExampleComponent);

// app.mount('#app');