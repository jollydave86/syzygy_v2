<?php

?>

<section>
    <div class="container">
        <button class="button js-button">Click David!</button>
    </div>
</section>

<style>
.button {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px; 
    height: 100px;
    transform: translate(-50%, -50%);
    padding: 1rem 2rem;
    font-size: 1.25rem;
    background-color: red;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transform: translate( 50%, -50%);
}
</style>

<script>
const runButton = document.querySelector(" .js-button" )

runButton.addEventListener( "mouseenter", () => {

     const directions = [
        { x: 100, y: 0 },
        { x: -100, y: 0 },
        { x: 0, y: 100 },
        { x: 0, y: -100 }
    ];

    const randomMove = directions[Math.floor(Math.random() * directions.length)];

    runButton.style.left = runButton.offsetLeft + randomMove.x + "px";
    runButton.style.top = runButton.offsetTop + randomMove.y + "px";
})
</script>