#!/usr/bin/python3
# -*- coding: utf-8 -*-
import json
import argparse
import base64
import warnings

def Str2Base64(txt):
    return base64.urlsafe_b64encode(txt.encode('utf-8')).decode()

def Conf2v2rayN(conf):
    '''
    Convert v2rayN format json file to v2rayN format vmess link
    '''

    link = 'vmess://' + Str2Base64(conf)
    return link


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='generate vmess links from v2rayN configuration file')
    parser.add_argument('CONF', help='v2rayN configration file')

    ARGS = parser.parse_args()
    with open(ARGS.CONF, 'r') as f:
        conf = json.load(f)
    c = str(conf)
    print(c)

    link = Conf2v2rayN(c)
    print(link)
