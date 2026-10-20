import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

document.querySelectorAll('[data-mask="money"]').forEach((input) => {
    const format = () => {
        let digits = input.value.replace(/\D/g, '');
        if (digits === '') return;
        digits = digits.replace(/^0+(?=\d)/, '');
        while (digits.length < 3) digits = '0' + digits;

        const cents = digits.slice(-2);
        const reais = digits.slice(0, -2).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        input.value = `${reais},${cents}`;
    };

    input.addEventListener('input', format);
    format();
});

document.querySelectorAll('[data-mask="year"]').forEach((input) => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/g, '').slice(0, 4);
    });
});
