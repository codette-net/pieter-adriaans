async function resetQrFactsheet(media_id) {
  const confirmReset = confirm(
    "Are you sure you want to reset the QR code and factsheet for this media item? This action cannot be undone."
  );
  if (!confirmReset) return;

  progressModal.style.display = "block";
  progressTitle.textContent = "Reset QR Code and Factsheet";
  progressMessage.textContent = "Resetting, please wait...";

  const formData = new FormData();
  formData.append("media_id", media_id);

  const response = await fetch("resetQrfactsheet.php", {
    method: "POST",
    body: formData,
  });

  const resultText = await response.text();
  console.log("response", resultText);

  if (!response.ok) {
    progressMessage.textContent = "Reset failed. Check console.";
    return;
  }

  progressMessage.textContent = "QR code and factsheet reset successfully!";
  setTimeout(() => {
    progressModal.style.display = "none";
    location.reload();
  }, 2000);
}
