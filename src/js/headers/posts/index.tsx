import * as React from 'react';
import render from '../../utils/render';

import { LogoIcon } from '../../utils/svg';
import ShareLink from './share-link';

import './posts-header.scss?global';

interface Props {
    title: string;
}

interface State {
    isActive: boolean;
    progressMax: number;
    progressCurrent: number;
}

declare const __header_posts: Props;

class PostHeader extends React.Component<Props, State> {
    postContentDiv: HTMLDivElement;

    constructor(props: Props) {
        super(props);
        this.state = {
            isActive: false,
            progressMax: 0,
            progressCurrent: 0,
        };
        const postContent: HTMLDivElement | null = document.querySelector(
            '.post-content',
        );
        if (!postContent) {
            throw new Error('Post content div element not found.');
        }
        this.postContentDiv = postContent;
        (document as any).addEventListener('scroll', this.handleProgress, {
            passive: true,
        });
    }

    componentWillUnmount() {
        document.removeEventListener('scroll', this.handleProgress);
    }

    handleProgress = (_e: UIEvent) => {
        const { offsetTop, offsetHeight } = this.postContentDiv;
        const { innerHeight: windowHeight, scrollY: progressCurrent } = window;
        const progressMax = offsetTop + offsetHeight - windowHeight;
        const isActive = progressCurrent >= offsetTop;
        this.setState(prevState => ({
            ...prevState,
            isActive,
            progressCurrent,
            progressMax,
        }));
    };

    render() {
        return (
            <header
                className={`header__posts ${
                    this.state.isActive ? 'header__posts--active' : ''
                }`}
            >
                <div className="header__posts__container">
                    <div className="header__posts__title">
                        <a href="/" aria-label="Navigate to home page">
                            <LogoIcon size={30} />
                        </a>
                        <h1>{this.props.title}</h1>
                    </div>
                    <div className="header__posts__share-link-container">
                        <div className="header__posts__share-label">
                            Share this
                        </div>
                        <ShareLink kind="twitter" title={this.props.title} />
                        <ShareLink kind="googleplus" title={this.props.title} />
                        <ShareLink kind="facebook" title={this.props.title} />
                    </div>
                </div>
                <progress
                    value={this.state.progressCurrent}
                    max={this.state.progressMax}
                />
            </header>
        );
    }
}

render(<PostHeader {...__header_posts} />, '#header__posts__root');
