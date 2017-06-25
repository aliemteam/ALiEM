import * as React from 'react';

export default class Tip extends React.PureComponent<any, any> {
    render() {
        const { isActive, text } = this.props;
        if (!isActive) return null;
        return <strong children={text} />;
    }
}
