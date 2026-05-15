export default class AdminPanel {

    audioPlayerCallback = null;

    constructor() {

        this.magicalFilterUX();

        let repeaterCloners = document.querySelectorAll('.bf-clone-repeater-item');

        repeaterCloners && repeaterCloners.forEach(node => {

            node.addEventListener('click', event => {

                setTimeout(() => this.magicalFilterUX(), 1000);
            });
        });

        this.audioPlayer();
    }

    magicalFilterUX() {

        let verticalSelect = document.querySelectorAll('.bf-repeater-item .bf-section-container[data-param-name="in"] .bf-controls .vertical');
        let horizontalSelect = document.querySelectorAll('.bf-repeater-item .bf-section-container[data-param-name="type"] .bf-controls .bf-advanced-select-group li');

        horizontalSelect && horizontalSelect.forEach(node => {

            if (!node.classList.contains('active')) return false;

            let parentSelector = '.bf-repeater-item';

            let repeater = node.closest(parentSelector);

            if (!repeater) {

                return false;
            }

            let label = repeater.querySelector('.handle-repeater-title-label');
            let inLabel = repeater.querySelector('.bf-section[data-id="in"] h3');

            let primaryColor = node.style.getPropertyValue('--primary-color');
            repeater.style = `--bf-primary-color: ${primaryColor}; --bf-primary-dark-color: var(--bf-primary-color)`;
            label.style.color = 'var(--bf-primary-color)';

            if (-1 === label.textContent.toLowerCase().indexOf('include')) {
                label.textContent = label.textContent.replace('Exclude', node.textContent.trim());
            } else {
                label.textContent = label.textContent.replace('Include', node.textContent.trim());
            }

            inLabel.textContent = `${node.textContent.trim()} In...`;
        });

        horizontalSelect && horizontalSelect.forEach(node => {
            node.addEventListener('click', event => {

                let target = event && event.target;

                let parentSelector = '.bf-repeater-item';

                let repeater = target.closest(parentSelector);

                if (!repeater) {

                    return false;
                }

                if ('LI' !== target.nodeName) target = node;

                let label = repeater.querySelector('.handle-repeater-title-label');
                let inLabel = repeater.querySelector('.bf-section[data-id="in"] h3');

                let primaryColor = target.style.getPropertyValue('--primary-color');
                repeater.style = `--bf-primary-color: ${primaryColor}; --bf-primary-dark-color: var(--bf-primary-color)`;
                label.style.color = 'var(--bf-primary-color)';

                if (-1 === label.textContent.toLowerCase().indexOf('include')) {
                    label.textContent = label.textContent.replace('Exclude', target.textContent.trim());
                } else {
                    label.textContent = label.textContent.replace('Include', target.textContent.trim());
                }

                inLabel.textContent = `${target.textContent.trim()} In...`;
            });
        });

        verticalSelect && verticalSelect.forEach(node => {

            node.addEventListener('click', event => {

                let target = event && event.target;

                let parentSelector = '.bf-repeater-item';

                if (!target.closest(parentSelector)) {

                    return false;
                }

                let repeater = target.closest(parentSelector);

                if (!repeater) {

                    return false;
                }

                let label = repeater.querySelector('.handle-repeater-title-label');
                let inLabel = repeater.querySelector('.bf-section[data-id="in"] h3');

                let filterType = repeater.querySelectorAll('.bf-advanced-select-group span.label');

                filterType && filterType.forEach(_node => {

                    if (!inLabel || !label || _node.closest('.vertical') || !_node.parentElement.classList.contains('active')) {

                        return false;
                    }

                    label.style.color = 'var(--bf-primary-color)';
                    label.textContent = `${_node.textContent}d in ${target.textContent}`;

                    inLabel.textContent = `${_node.textContent} In...`;
                });
            });
        });
    }

    audioPlayer() {

        let sounds = document.querySelectorAll('.fields-group .bf-nonrepeater-section .bf-advanced-select.vertical li');

        sounds && sounds.forEach(node => {
            if (-1 === node.closest('.bf-nonrepeater-section').getAttribute('data-id').indexOf('audio-alert/sound')) {
                return false;
            }

            let playIcon = document.createElement('span');
            let playSVG = '<svg class="player-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">\n' +
                '  <path id="Path_969" data-name="Path 969" d="M113.6,33.6a10,10,0,1,0,10,10A10.007,10.007,0,0,0,113.6,33.6Zm0,.909a9.091,9.091,0,1,1-9.091,9.091A9.084,9.084,0,0,1,113.6,34.511Zm-2.415,4.773a1.029,1.029,0,0,0-.994,1.037v6.555a1.025,1.025,0,0,0,1.527.909L117.4,44.5a1.032,1.032,0,0,0,0-1.79l-5.682-3.288a1.062,1.062,0,0,0-.533-.135Z" transform="translate(-103.6 -33.602)" fill="#fff"/>\n' +
                '</svg>\n';
            playIcon.innerHTML = playSVG;
            playIcon.className = 'cp-sound-icon-wrapper';

            let svgElement = playIcon.querySelector('svg path');
            if (!svgElement) return false;

            node.style.position = 'relative';
            node.classList.add('cp-audio-item');
            node.appendChild(playIcon);

            this.audioPlayerCallback = event => this.play(event, {
                node,
                playSVG,
                playIcon,
                svgElement
            });

            node.addEventListener('click', this.audioPlayerCallback, true);
        });
    }

    play(event, opt) {

        let audioPlayerWrapper = opt.node.querySelector('.cp-sound-icon-wrapper');

        if (!audioPlayerWrapper) {

            return false;
        }

        if (!opt.node.getAttribute('data-value')) {

            return false;
        }

        if ('undefined' === typeof AudioAlertL10n || !AudioAlertL10n['assets-url']) {

            return false;
        }

        let sound = AudioAlertL10n['assets-url'] + 'sounds/' + opt.node.getAttribute('data-value');
        let audio = new Audio(sound);

        let closest = event.target.closest('.bf-section-container');

        if (closest) {

            let volume = closest?.nextElementSibling?.querySelector('input.bf-slider-input[type="hidden"]');

            audio.volume = volume && volume.value && (volume.value / 100);
        }

        audio.addEventListener('loadeddata', () => {
            let duration = audio.duration;
            // The duration variable now holds the duration (in seconds) of the audio clip

            const play = async () => {

                opt.playIcon.innerHTML = '<svg class="player-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">\n' +
                    '  <g id="Group_1041" data-name="Group 1041" transform="translate(-801.157 -561)">\n' +
                    '    <path id="Path_971" data-name="Path 971" d="M113.6,33.6a10,10,0,1,0,10,10A10.007,10.007,0,0,0,113.6,33.6Zm0,.909a9.091,9.091,0,1,1-9.091,9.091A9.084,9.084,0,0,1,113.6,34.511Z" transform="translate(697.557 527.398)" fill="#fff"/>\n' +
                    '    <rect id="Rectangle_245" data-name="Rectangle 245" width="2" height="8" transform="translate(808 567)" fill="#c8c8c8"/>\n' +
                    '    <rect id="Rectangle_246" data-name="Rectangle 246" width="2" height="8" transform="translate(812 567)" fill="#c8c8c8"/>\n' +
                    '  </g>\n' +
                    '</svg>\n';

                await audio.play();
            }

            play().then(res => {

                setTimeout(() => {
                    opt.playIcon.innerHTML = opt.playSVG;

                    let svgElement = opt.playIcon.querySelector('svg path');
                    if (!svgElement) return false;

                }, duration * 1000);
            });
        });
    }
}
