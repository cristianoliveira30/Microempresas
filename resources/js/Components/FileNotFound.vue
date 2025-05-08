<template>
    <div class="container-folder">
        <div class="folder">
            <div class="top"></div>
            <div class="bottom"></div>
        </div>
        <div class="title">
            <p>Sem dados até o momento...</p>
        </div>
    </div>
</template>
<style>
.container-folder {
    width: fit-content;
    gap: 10px
}

.folder {
    width: min-content;
    margin: auto;
    animation: float 4s infinite linear;
}

.folder .top {
    background-color: #FF8F56;
    width: 60px;
    height: 12px;
    border-top-right-radius: 10px;
}

.folder .bottom {
    background-color: #FFCE63;
    width: 100px;
    height: 70px;
    box-shadow: 6px 4px 10px 0px #283149;
    border-top-right-radius: 8px;
}

.container-folder p {
    color: red;
    font-size: 1.3rem;
    font-weight: bolder;
}

@keyframes float {
    0% {
        transform: translatey(0px);
    }

    50% {
        transform: translatey(-15px);
    }

    100% {
        transform: translatey(0px);
    }
}
</style>
