const navToggle = document.querySelector('[data-nav-toggle]');
const navPanel = document.querySelector('[data-nav-panel]');

if (navToggle && navPanel) {
    navToggle.addEventListener('click', () => {
        navPanel.classList.toggle('open');
    });
}

document.querySelectorAll('[data-line-chart]').forEach((canvas) => {
    const series = JSON.parse(canvas.dataset.lineChart || '[]');
    const context = canvas.getContext('2d');
    const dpr = window.devicePixelRatio || 1;
    const width = canvas.clientWidth || 600;
    const height = 280;

    canvas.width = width * dpr;
    canvas.height = height * dpr;
    context.scale(dpr, dpr);
    context.clearRect(0, 0, width, height);

    if (!series.length) {
        context.fillStyle = '#94a3b8';
        context.font = '500 13px "Plus Jakarta Sans", sans-serif';
        context.fillText('No cashflow data recorded yet.', 24, 140);
        return;
    }

    const padding = 34;
    const chartWidth = width - padding * 2;
    const chartHeight = height - padding * 2;
    
    const maxValue = Math.max(...series.flatMap((item) => [Number(item.income || 0), Number(item.expense || 0)]), 1);
    
    const pointsFor = (key) => series.map((item, index) => ({
        x: padding + (index * (chartWidth / Math.max(series.length - 1, 1))),
        y: height - padding - ((Number(item[key] || 0) / maxValue) * chartHeight),
        label: item.label,
    }));

    const drawGrid = () => {
        context.strokeStyle = 'rgba(241, 245, 249, 1)';
        context.lineWidth = 1;
        for (let index = 0; index <= 4; index += 1) {
            const y = padding + (index * (chartHeight / 4));
            context.beginPath();
            context.moveTo(padding, y);
            context.lineTo(width - padding, y);
            context.stroke();
        }
    };

    const drawLine = (points, stroke, fill) => {
        if (!points.length) return;

        // Draw fill area
        context.beginPath();
        points.forEach((point, index) => {
            if (index === 0) {
                context.moveTo(point.x, point.y);
            } else {
                context.lineTo(point.x, point.y);
            }
        });
        context.lineTo(points.at(-1).x, height - padding);
        context.lineTo(points[0].x, height - padding);
        context.closePath();
        context.fillStyle = fill;
        context.fill();

        // Draw line stroke
        context.beginPath();
        points.forEach((point, index) => {
            if (index === 0) {
                context.moveTo(point.x, point.y);
            } else {
                context.lineTo(point.x, point.y);
            }
        });
        context.strokeStyle = stroke;
        context.lineWidth = 2.5;
        context.stroke();

        // Draw sleek points
        points.forEach((point) => {
            context.beginPath();
            context.arc(point.x, point.y, 4, 0, Math.PI * 2);
            context.fillStyle = '#ffffff';
            context.fill();
            context.strokeStyle = stroke;
            context.lineWidth = 2;
            context.stroke();
        });
    };

    drawGrid();
    drawLine(pointsFor('income'), '#6366f1', 'rgba(99, 102, 241, 0.05)');
    drawLine(pointsFor('expense'), '#f43f5e', 'rgba(244, 63, 94, 0.03)');

    // Draw Labels
    context.fillStyle = '#64748b';
    context.font = '600 10px "Plus Jakarta Sans", system-ui, sans-serif';
    series.forEach((item, index) => {
        const x = padding + (index * (chartWidth / Math.max(series.length - 1, 1)));
        const textWidth = context.measureText(item.label).width;
        context.fillText(item.label, x - textWidth / 2, height - 10);
    });
});

