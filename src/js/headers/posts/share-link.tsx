import * as React from 'react';

type ShareKind = 'facebook' | 'googleplus' | 'twitter';
type ShareResolvers = { [P in ShareKind]: (title?: string) => string };

interface Props {
    kind: ShareKind;
    title: string;
}

const resolvers: ShareResolvers = {
    facebook() {
        return `https://facebook.com/sharer/sharer.php?u=${
            window.location.href
        }`;
    },
    googleplus() {
        return `https://plus.google.com/share?url=${window.location.href}`;
    },
    twitter(title) {
        // prettier-ignore
        return `https://twitter.com/share?text=${encodeURIComponent(title!)}&url=${window.location.href}`;
    },
};

export default class ShareLink extends React.PureComponent<Props, {}> {
    handleClick = (e: React.MouseEvent<HTMLAnchorElement>) => {
        e.preventDefault();
        window.open(
            e.currentTarget.href,
            `share-${this.props.kind}`,
            'height=400,width=600',
        );
    };

    render() {
        const { kind, title } = this.props;
        return (
            <a
                className={`header__posts__share-link header__posts__share-link--${kind}`}
                href={resolvers[kind](title)}
                onClick={this.handleClick}
            >
                <img
                    width="16"
                    height="16"
                    src={`https://cdn.jsdelivr.net/npm/simple-icons@1.2.7/icons/${kind}.svg`}
                />
            </a>
        );
    }
}
