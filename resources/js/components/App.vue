<template>
    <div class="container">
        <h1>hello</h1>
        <div id="nav">
            <router-link to="/">Example</router-link> |
            <router-link to="/test">Test</router-link>
        </div>
        <!-- <p>This is a paragraph.</p> -->

        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "App"
};
</script>

<style></style>
