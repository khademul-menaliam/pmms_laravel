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
        context.fillStyle = '#64748b';
        context.fillText('No chart data yet.', 24, 40);
        return;
    }

    const padding = 28;
    const maxValue = Math.max(...series.flatMap((item) => [Number(item.income || 0), Number(item.expense || 0)]), 1);
    const pointsFor = (key) => series.map((item, index) => ({
        x: padding + (index * ((width - padding * 2) / Math.max(series.length - 1, 1))),
        y: height - padding - ((Number(item[key] || 0) / maxValue) * (height - padding * 2)),
        label: item.label,
    }));

    const drawGrid = () => {
        context.strokeStyle = 'rgba(148,163,184,0.25)';
        context.lineWidth = 1;
        for (let index = 0; index < 5; index += 1) {
            const y = padding + (index * ((height - padding * 2) / 4));
            context.beginPath();
            context.moveTo(padding, y);
            context.lineTo(width - padding, y);
            context.stroke();
        }
    };

    const drawLine = (points, stroke, fill) => {
        context.beginPath();
        points.forEach((point, index) => {
            if (index === 0) {
                context.moveTo(point.x, point.y);
            } else {
                context.lineTo(point.x, point.y);
            }
        });
        context.strokeStyle = stroke;
        context.lineWidth = 3;
        context.stroke();

        context.lineTo(points.at(-1).x, height - padding);
        context.lineTo(points[0].x, height - padding);
        context.closePath();
        context.fillStyle = fill;
        context.fill();

        points.forEach((point) => {
            context.beginPath();
            context.arc(point.x, point.y, 4, 0, Math.PI * 2);
            context.fillStyle = stroke;
            context.fill();
        });
    };

    drawGrid();
    drawLine(pointsFor('income'), '#4f46e5', 'rgba(79, 70, 229, 0.12)');
    drawLine(pointsFor('expense'), '#ef4444', 'rgba(239, 68, 68, 0.08)');

    context.fillStyle = '#64748b';
    context.font = '12px Segoe UI';
    series.forEach((item, index) => {
        const x = padding + (index * ((width - padding * 2) / Math.max(series.length - 1, 1)));
        context.fillText(item.label, x - 18, height - 8);
    });
});
