const canvas = document.querySelectorAll('.dataForStat');
canvas.forEach(canva => {
    const data = JSON.parse(canva.dataset.stats);
    const ctx = document.getElementById(canva.id).getContext('2d');
    var vals = [];
    var labs = [];
    data.forEach(element => {
        vals.push(element['value']);
        labs.push(element['label']);
    });
    
    const myChart = new Chart(ctx, {
        type: canva.dataset.type,
        data: {
            labels: labs,
            datasets: [{
                label: "# d'adhérents par adhésion",
                data: vals,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
       
    });
});