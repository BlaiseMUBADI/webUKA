<script>
    function recherchetable()
    {
        const text_rech=document.getElementById("zone_rech_inscription");
        const filter=text_rech.value.toLowCase();
        const table = document.getElementId("table_inscrit");
        const rows = table.getElementByName("tr");

        for (let i =1; i<rows.length;i++) {
            const cells=rows[i].getElementByName("td");
            let found = false;

            for(let j=0; j < cells.length; j++){
                const cell =cells[j];
                if(cell){
                    if (cell.innerHTML.toLowCase().indexOf(filter)>-1) {
                        found=true;
                        break;
                    }
                }
            }
            rows[i].style.display=found?"":"none";
        }
    }
</script>