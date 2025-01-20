class Tooltip {
    constructor() {
        this.tooltip = document.createElement('div');
        this.tooltip.className = 'tooltip';
        document.body.appendChild(this.tooltip);
        this.bindEvents();
    }

    bindEvents() {
        document.addEventListener('mouseover', (event) => {
            const target = event.target.closest('[data-tooltip]');
            if (target.hasAttribute('data-tooltip')) {
                this.showTooltip(target);
            }
        });

        document.addEventListener('mouseout', (event) => {
            const target = event.target.closest('[data-tooltip]');
            if (target.hasAttribute('data-tooltip')) {
                this.hideTooltip();
            }
        });
    }

    showTooltip(target) {
        const tooltipText = target.getAttribute('data-tooltip');
        const placement = target.getAttribute('data-placement') || 'top';

        this.tooltip.textContent = tooltipText;
        this.tooltip.setAttribute('data-placement', placement);
        this.tooltip.style.visibility = 'visible';
        this.tooltip.style.opacity = '1';

        const targetRect = target.getBoundingClientRect();
        const tooltipRect = this.tooltip.getBoundingClientRect();

        let top, left;

        switch (placement) {
            case 'top':
                top = targetRect.top - tooltipRect.height - 8;
                left = targetRect.left + (targetRect.width - tooltipRect.width) / 2;
                break;
            case 'bottom':
                top = targetRect.bottom + 8;
                left = targetRect.left + (targetRect.width - tooltipRect.width) / 2;
                break;
            case 'left':
                top = targetRect.top + (targetRect.height - tooltipRect.height) / 2;
                left = targetRect.left - tooltipRect.width - 8;
                break;
            case 'right':
                top = targetRect.top + (targetRect.height - tooltipRect.height) / 2;
                left = targetRect.right + 8;
                break;
            default:
                top = targetRect.top - tooltipRect.height - 8;
                left = targetRect.left + (targetRect.width - tooltipRect.width) / 2;
        }

        this.tooltip.style.top = `${top}px`;
        this.tooltip.style.left = `${left}px`;
    }

    hideTooltip() {
        this.tooltip.style.visibility = 'hidden';
        this.tooltip.style.opacity = '0';
    }
}

// Khởi tạo Tooltip
new Tooltip();
