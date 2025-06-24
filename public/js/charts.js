//esperando o DOM carregar completamento para receber os dados e exibir nos gráficos
document.addEventListener('DOMContentLoaded', function () {
  //fetch no arquivo que dava echo nos dados como json
  fetch('../../../src/database/ChartsData.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Erro de conexão');
      }
      return response.json();
    })
    .then(booksData => {
      //adicionando dados nos gráficos
      const ctxStatus = document.getElementById('chartStatus').getContext('2d');
      const chartStatus = new Chart(ctxStatus, {
        type: 'bar',
        data: {
          labels: ['Lidos', 'Em Leitura', 'Por Ler'],
          datasets: [{
            data: [
              booksData.total_livros_lidos,
              booksData.total_livros_leitura,
              booksData.total_livros_ler
            ],
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          },
          scales: {
            x: { display: false },
            y: { beginAtZero: true }
          }
        }
      });

      const ctxPages = document.getElementById('chartPages').getContext('2d');
      const chartPages = new Chart(ctxPages, {
        type: 'pie',
        data: {
          labels: ['Lidos', 'Em Leitura', 'Por Ler'],
          datasets: [{
            data: [
              booksData.total_paginas_lidos,
              booksData.total_paginas_leitura,
              booksData.total_paginas_ler
            ],
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: true },
            tooltip: { enabled: true }
          }
        }
      });

      const ctxGenre = document.getElementById('chartGenre').getContext('2d');
      const chartGenre = new Chart(ctxGenre, {
        type: 'bar',
        data: {
          labels: [
            'Autoajuda', 'Aventura', 'Biografia', 'Drama', 'Fantasia',
            'Ficção', 'Ficção Científica', 'Filosofia', 'História',
            'Infantil', 'Livro Didáticos', 'Mistério', 'Não-Ficção',
            'Poesia', 'Romance', 'Suspense', 'Tecnologia'
          ],
          datasets: [{
            data: [
              booksData.total_autoajuda,
              booksData.total_aventura,
              booksData.total_biografia,
              booksData.total_drama,
              booksData.total_fantasia,
              booksData.total_ficcao,
              booksData.total_ficcao_cientifica,
              booksData.total_filosofia,
              booksData.total_historia,
              booksData.total_infantil,
              booksData.total_livros_didaticos,
              booksData.total_misterio,
              booksData.total_nao_ficcao,
              booksData.total_poesia,
              booksData.total_romance,
              booksData.total_suspense,
              booksData.total_tecnologia
            ],
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(153, 102, 255, 0.7)',
              'rgba(255, 159, 64, 0.7)',
              'rgba(199, 199, 199, 0.7)',
              'rgba(83, 102, 255, 0.7)',
              'rgba(40, 167, 69, 0.7)',
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(153, 102, 255, 0.7)',
              'rgba(255, 159, 64, 0.7)',
              'rgba(199, 199, 199, 0.7)',
              'rgba(83, 102, 255, 0.7)',
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(199, 199, 199, 1)',
              'rgba(83, 102, 255, 1)',
              'rgba(40, 167, 69, 1)',
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(199, 199, 199, 1)',
              'rgba(83, 102, 255, 1)',
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          },
          scales: {
            x: { display: true },
            y: { beginAtZero: true }
          }
        }
      });

      const ctxTotalPages = document.getElementById('chartTotalPages').getContext('2d');
      const chartTotalPages = new Chart(ctxTotalPages, {
        type: 'bar',
        data: {
          labels: ['<200', '200-400', '400-600', '600-800', '800-1000', '>1000'],
          datasets: [{
            data: [
              booksData.livros_200,
              booksData.livros_200_400,
              booksData.livros_400_600,
              booksData.livros_600_800,
              booksData.livros_800_1000,
              booksData.livros_1000
            ],
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(153, 102, 255, 0.7)',
              'rgba(255, 159, 64, 0.7)',
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          },
          scales: {
            x: { display: true },
            y: { beginAtZero: true }
          }
        }
      });
    })
    .catch(error => {
      console.error('Erro ao receber dados:', error);
    });
});