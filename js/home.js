const gridData = [
    {
        img: "img/card1.jpg",
        title: "HHAT功能介绍",
        time: "2026-02-03",
        link: "https://www.example.com/1"
    },
    {
        img: "img/card2.jpg",
        title: "使用教程详解",
        time: "2026-02-02",
        link: "https://www.example.com/2"
    },
    {
        img: "img/card3.jpg",
        title: "常见问题解答",
        time: "2026-02-01",
        link: "https://www.example.com/3"
    },
    {
        img: "img/card4.jpg",
        title: "高级功能演示",
        time: "2026-01-31",
        link: "https://www.example.com/4"
    },
    {
        img: "img/card5.jpg",
        title: "更新日志V1.0",
        time: "2026-01-30",
        link: "https://www.example.com/5"
    },
    {
        img: "img/card6.jpg",
        title: "用户案例分享",
        time: "2026-01-29",
        link: "https://www.example.com/6"
    },
    {
        img: "img/card7.jpg",
        title: "开发计划预告",
        time: "2026-01-28",
        link: "https://www.example.com/7"
    },
    {
        img: "img/card8.jpg",
        title: "资源下载中心",
        time: "2026-01-27",
        link: "https://www.example.com/8"
    },
    {
        img: "img/card9.jpg",
        title: "联系我们",
        time: "2026-01-26",
        link: "https://www.example.com/9"
    }
];

const gridContainer = document.getElementById('gridContainer');

function renderGrid() {
    gridData.forEach(item => {
        const card = document.createElement('div');
        card.className = 'grid-card';
        card.innerHTML = `
            <img src="${item.img}" alt="${item.title}" class="card-img">
            <div class="card-content">
                <h3 class="card-title">${item.title}</h3>
                <p class="card-time">
                    <i class="far fa-clock"></i>${item.time}
                </p>
            </div>
        `;
        card.addEventListener('click', () => {
            window.open(item.link, '_blank');
        });
        gridContainer.appendChild(card);
    });
}

window.onload = renderGrid;
