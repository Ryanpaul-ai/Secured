domButton.addEventListener("click", function (event) {
  event.preventDefault();

  const payload = domInput.value.trim();

  if (!payload) {
    if (status) {
      status.textContent = "Please enter a payload.";
    }
    return;
  }

  domOutput.replaceChildren();

  if (status) {
    status.textContent = "Payload processed safely. No code was executed.";
  }
});
