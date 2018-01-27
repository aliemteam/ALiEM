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
                    >
                        <Icons.facebook />
                    </a>
                    <a
                        className="header__social-icon"
                        href="/feed"
                        aria-label="RSS Feed Page"
                    >
                        <Icons.rss />
                    </a>
                    <a
                        className="header__social-icon"
                        href="https://twitter.com/ALiEMteam"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Twitter Page"
                    >
                        <Icons.twitter />
                    </a>
                    <a
                        className="header__social-icon"
                        href="https://plus.google.com/+Academiclifeinem"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Google Plus Page"
                    >
                        <Icons.googleplus />
                    </a>
                    <a
                        className="header__social-icon"
                        href="https://soundcloud.com/academic-life-in-em"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="SoundCloud Page"
                    >
                        <Icons.soundcloud />
                    </a>
                </div>
            </div>
        );
    }
}
