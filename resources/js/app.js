import "./bootstrap";
import feather from "feather-icons";

document.addEventListener("DOMContentLoaded", () => {
    feather.replace();
});

document.addEventListener("livewire:navigated", () => {
    feather.replace();
});
