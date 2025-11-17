function filterTable() {
  const input = document.getElementById("searchBox").value.toLowerCase();
  const rows = document.querySelectorAll("#donationTable tbody tr");

  rows.forEach(row => {
    const cells = Array.from(row.cells);
    const match = cells.some(cell => cell.textContent.toLowerCase().includes(input));
    row.style.display = match ? "" : "none";
  });
}

function sortTable(n) {
  const table = document.getElementById("donationTable");
  let switching = true;
  let dir = "asc";
  let switchCount = 0;

  while (switching) {
    switching = false;
    const rows = table.rows;
    for (let i = 1; i < rows.length - 1; i++) {
      let shouldSwitch = false;
      const x = rows[i].getElementsByTagName("TD")[n];
      const y = rows[i + 1].getElementsByTagName("TD")[n];
      let cmpX = x.textContent.toLowerCase();
      let cmpY = y.textContent.toLowerCase();

      if (n === 1) { // numeric sort for amount
        cmpX = parseFloat(cmpX.replace(/[₱,]/g, ""));
        cmpY = parseFloat(cmpY.replace(/[₱,]/g, ""));
      }

      if (dir === "asc" ? cmpX > cmpY : cmpX < cmpY) {
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchCount++;
    } else if (switchCount === 0 && dir === "asc") {
      dir = "desc";
      switching = true;
    }
  }
}
