// tslint:disable:react-a11y-anchors
import * as React from 'react';
import { Icons } from './graphics';

export default class extends React.PureComponent {
    render() {
        return (
            <div className="header-secondary">
                <div className="header-secondary__container">
                    <a
                        className="header__social-icon"
                        href="https://www.facebook.com/academiclifeinem"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook Page"
                        title="Facebook"
                    >
                        <Icons.Facebook />
                    </a>
                    <a
                        className="header__social-icon"
                        href="https://www.instagram.com/aliemteam/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram Page"
                        title="Instagram"
                    >
                        <Icons.Instagram />
                    </a>
                    <a
                        className="header__social-icon"
                        href="https://twitter.com/ALiEMteam"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Twitter Page"
                        title="Twitter"
                    >
                        <Icons.Twitter />
                    </a>
                    <a
                        className="header__social-icon"
                        href="https://soundcloud.com/academic-life-in-em"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="SoundCloud Page"
                        title="Soundcloud"
                    >
                        <Icons.Soundcloud />
                    </a>
                    <a
                        className="header__social-icon"
                        href="/feed"
                        aria-label="RSS Feed Page"
                        title="RSS Feed"
                    >
                        <Icons.RSS />
                    </a>
                </div>
            </div>
        );
    }
}
