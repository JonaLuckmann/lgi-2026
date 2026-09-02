document.querySelectorAll("[data-confirm]").forEach((formulario) => {
  formulario.addEventListener("submit", (evento) => {
    if (!window.confirm(formulario.dataset.confirm)) evento.preventDefault();
  });
});

document.querySelectorAll(".grade-input").forEach((campo) => {
  campo.addEventListener("input", () => {
    campo.closest("tr")?.classList.add("has-changes");
  });
});
